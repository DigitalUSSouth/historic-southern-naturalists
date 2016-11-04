/**
 * symbiota.js
 *
 * The data miner for the `plants` table within the `hsn` database.
 *
 * Note: Some `id` values will return as strings, other will be integers.
 *       Convert them all to strings unless you want to type check every time.
 *
 * TODO: Handle if a plant was deleted remotely.
 */

class Symbiota {
  /**
   * Step 0 - Constructor
   *
   * Initialize all class-wide variables.
   */
  constructor() {
    this.logger('Initializing.');

    // Imports.
    this.fs   = require('fs');
    this.path = require('path');

    // Installed via `npm install` in the project root.
    const pg       = require('pg');
    this.download  = require('download');
    this.babyparse = require('babyparse');

    // CSV file reading input from.
    this.csv = 'occurrences.csv';

    // URL to download zip archive.
    this.url = 'http://herbarium.biol.sc.edu/floracaroliniana/webservices/dwc/dwcapubhandler.php?collid=1&cond=collectioncode-equals:ACM';

    // Connection between this process and the database.
    this.client = new pg.Client(this.retrieveConnectionString());

    // Folder all this stuff is happening in.
    this.folder = './tmp/';

    this.client.connect((error) => {
      if (error) {
        throw error;
      }

      this.logger('Connected to the PostgreSQL database.');

      this.retrieveData();
    });
  }

  /**
   * Step 0.a - Retrieve Connection String
   *
   * Retrieves the connection string from locally created file.
   */
  retrieveConnectionString() {
    const fs = require('fs');

    return JSON.parse(fs.readFileSync('pg-connect.json').toString()).js;
  }

  /**
   * Step 1 - Download
   *
   * Downloads a file from a given URL and then extracts its contents.
   */
  retrieveData() {
    this.logger('Retrieving archived file.');

    this.download(this.url, this.folder, { extract: true }).then(() => {
      this.logger('Archived file retrieved and extracted into ' + this.folder);

      this.readData();
    });
  }

  /**
   * Step 2 - Read Data
   *
   * Reads in file content from a given UNIX-style file path.
   */
  readData() {
    this.logger('Reading in ' + this.csv);

    this.fs.readFile(this.folder + this.csv, 'utf-8', (error, data) => {
      if (error) {
        throw error;
      }

      this.parseData(data);
    });
  }

  /**
   * Step 3 - Parse Data
   *
   * Utilizing PapaParse, parse the CSV data into JSON format.
   *
   * Note: This is actually babyparse, a forked repo for NPM.
   *
   * @param {String} data -- CSV string to be parsed into JSON.
   */
  parseData(data) {
    this.logger('Parsing the CSV data.');

    const results = this.babyparse.parse(data, {
      header: true,
      error(error) {
        throw error;
      }
    });

    this.manipulateDatabase(results);
  }

  /**
   * Step 4 - Cross-Reference with Database
   *
   * Extracts data from ParaParse and determines if the content exists within
   * the database or not. If it does, update, otherwise, insert.
   *
   * @param {Object} results -- The results object from PapaParse.
   */
  manipulateDatabase(results) {
    this.logger('Manipulating the database.');

    for (let i = 0; i < results.data.length; i++) {
      const item = results.data[i];

      // This is actually executed at least once. -_-
      if (!Object.prototype.hasOwnProperty.call(item, 'id') || item.id.toString().trim() === '') {
        continue;
      }

      // Query the database to see if the id exists.
      this.client.query('SELECT id FROM plants WHERE id = $1::character(5)', [item.id.toString()], (error, result) => {
        if (error) {
          this.logger(item.id + ' - Incoming error.');

          throw error;
        }

        // Determine if we want to update or insert it.
        if (result.rows.length) {
          this.updateRow(item);
        } else {
          this.insertRow(item);
        }
      });
    }

    // Initialize a two second timeout to assure any future queries
    // are completed. There might be a better way to do this.
    setTimeout(() => {
      this.closeDatabaseConnection();
    }, 2000);
  }

  /**
   * Step 4.a - Update Fields
   *
   * Updates the database with the assumption that Symbiota is more accurate.
   *
   * @param {Object} item -- The plant from Symbiota.
   */
  updateRow(item) {
    this.logger(item.id + ' - Detected a possible update.');

    let update = '';

    this.client.query('SELECT * FROM plants WHERE id = $1::character(5)', [item.id.toString()], (error, result) => {
      if (error) {
        this.logger(item.id + ' - Incoming error.');

        throw error;
      }

      // Run through the results, cross-reference.
      for (let key in result.rows[0]) {
        const remoteKey   = this.convertKeyToRemote(key);
        const remoteValue = item[remoteKey].toString().replace(/'/g, '&#39;').trim();

        if (this.isCompatible(remoteKey) && result.rows[0][key].toString().trim() != remoteValue) {
          update += key + ' = \'' + remoteValue + '\', ';
        }
      }

      if (update === '') {
        this.logger(item.id + ' - Nothing to update.');

        return;
      }

      update = update.substring(0, update.length - 2);

      this.logger(item.id + ' - Attempting to update: ' + update);

      // Tell the database to update.
      this.client.query('UPDATE plants SET ' + update + ' WHERE id = $1::character(5)', [item.id.toString()], (error, result) => {
        if (error) {
          this.logger(item.id + ' - Incoming error.');

          throw error;
        }

        this.logger(item.id + ' - Successfully updated information.');
      });
    });
  }

  /**
   * Step 4.b - Insert Fields
   *
   * Creates a new in the database.
   *
   * @param {Object} item -- The plant from Symbiota.
   */
  insertRow(item) {
    this.logger(item.id + ' - Detected an insert.');

    let insert = '';
    let values = '';

    // Run through each key => value, and determine if we want it.
    for (let key in item) {
      if (this.isCompatible(key) && item[key].toString().trim() !== '') {
        insert += this.convertKeyToDatabase(key) + ', ';
        values += '\'' + item[key].toString().replace(/'/g, '&#39;').trim() + '\', ';
      }
    }

    insert = insert.substring(0, insert.length - 2);
    values = values.substring(0, values.length - 2);

    this.logger(item.id + ' - Attempting to insert: ' + insert);
    this.logger(item.id + ' - Attempting to values: ' + values);

    // Tell the database to insert.
    this.client.query('INSERT INTO plants (' + insert + ') VALUES (' + values + ')', (error, result) => {
      if (error) {
        this.logger(item.id + ' - Incoming error.');

        throw error;
      }

      this.logger(item.id + ' - Successfully inserted information.');
    });
  }

  /**
   * Step 5 - Shutdown
   *
   * Closes the database connection between this process and the database.
   * Also removes the temporary folder created to house the archived files.
   */
  closeDatabaseConnection() {
    this.client.end((error) => {
      if (error) {
        throw error;
      }
    });

    this.deleteFolder(this.folder);
  }

  /**
   * Step 5.a - Folder Deletion
   *
   * Recursively delete the specified folder and its contents.
   *
   * @param {String} path -- Folder path in UNIX style.
   */
  deleteFolder(path) {
    if (this.fs.existsSync(path)) {
      this.fs.readdirSync(path).forEach((file, index) => {
        const current = path + '/' + file;

        if (this.fs.lstatSync(current).isDirectory()) {
          this.deleteFolder(current);
        } else {
          this.fs.unlinkSync(current);
        }
      });

      this.fs.rmdirSync(path);
    }
  }

  /**
   * Internal function to determine if the fields coming from Symbiota are
   * fields that we want to worry about.
   *
   * @param  {String}  key -- The current field key.
   * @return {Boolean}
   */
  isCompatible(key) {
    const acceptable = [
      'id',
      'county',
      'country',
      'decimalLatitude',
      'decimalLongitude',
      'eventDate',
      'genus',
      'habitat',
      'family',
      'identifiedBy',
      'locality',
      'recordedBy',
      'scientificName',
      'stateProvince'
    ];

    return acceptable.indexOf(key) > -1;
  }

  /**
   * Internal function to convert the key from Symbiota column header to our
   * column headers, since PostgreSQL doesn't seem to be a fan of camel-case.
   *
   * @param  {String} key -- The key from Symbiota.
   * @return {String}
   */
  convertKeyToDatabase(key) {
    const convert = {
      decimalLatitude:  'decimal_latitude',
      decimalLongitude: 'decimal_longitude',
      eventDate:        'event_date',
      identifiedBy:     'identified_by',
      recordedBy:       'recorded_by',
      scientificName:   'scientific_name',
      stateProvince:    'state_province'
    };

    return Object.prototype.hasOwnProperty.call(convert, key)
      ? convert[key]
      : key;
  }

  /**
   * Internal function to convert the key from our column headers to the
   * column headers for Symbiota.
   *
   * @param  {String} key -- The key from PostgreSQL.
   * @return {String}
   */
  convertKeyToRemote(key) {
    const convert = {
      decimal_latitude:  'decimalLatitude',
      decimal_longitude: 'decimalLongitude',
      event_date:        'eventDate',
      identified_by:     'identifiedBy',
      recorded_by:       'recordedBy',
      scientific_name:   'scientificName',
      state_province:    'stateProvince'
    };

    return Object.prototype.hasOwnProperty.call(convert, key)
      ? convert[key]
      : key;
  }

  /**
   * Internal logging function.
   *
   * @param {String} text -- The text to log.
   */
  logger(text) {
    console.log(new Date() + ' ' + text);
  }
}

new Symbiota();
