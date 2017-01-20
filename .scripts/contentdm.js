/**
 * contentdm.js
 *
 * The data miner for the `manuscripts` table within the `hsn` database.
 *
 * Note: All `pointer` and `parentobject` values will be returned as an integer.
 *       They must be converted to a string to properly work here.
 *
 * TODO: Determine what to do if there are over 1024 results returned.
 * TODO: Handle if a manuscript was deleted remotely.
 */

class Content {
  /**
   * Step 0 - Constructor
   *
   * Initializes all class-wide variables and connect to the database.
   */
  constructor() {
    console.log(new Date() + ' Initializing.');

    // Installed via `npm install` in the project root.
    const pg = require('pg');

    // (Future) data returned from CONTENTdm.
    this.data = '';

    // Default installed with Node.
    this.http = require('http');

    // Connection between this process and the database.
    this.client = new pg.Client(this.retrieveConnectionString());

    this.client.connect((error) => {
      if (error) {
        throw error;
      }

      this.retrieveResults();
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
   * Step 1 - Retrieve Results
   *
   * Queries a wildcard search to CONTENTdm and grabs all results.
   */
  retrieveResults() {
    console.log(new Date() + ' Retrieving results.');

    this.http.get({
      host: 'digital.tcl.sc.edu',
      port: '81',
      path: '/dmwebservices/?q=dmQuery/hsn/CISOSEARCHALL^*^any/contri!covera!date!descri!media!publis!relati!subjec!title!transc/0/1024/0/0/0/0/0/1/json',
      headers: {
        'Content-Type': 'application/json'
      }
    }, (result) => {
      result.on('data', (chunk) => {
        this.data += chunk.toString();
      });

      result.on('end', () => {
        this.parseData();
      });
    });
  }

  /**
   * Step 2 - Parse
   *
   * Attempts to parse the retrieved data into a JavaScript-acceptable JSON
   * format.
   */
  parseData() {
    console.log(new Date() + ' Parsing.');

    try {
      this.data = JSON.parse(this.data);
    } catch (error) {
      throw error;
    }

    this.manipulateDatabase();
  }

  /**
   * Step 3 - Cross-Reference with Database
   *
   * Initially looks if the pointer is within the database and if so, updates
   * the fields to match CONTENTdm, otherwise, inserts new data.
   */
  manipulateDatabase() {
    console.log(new Date() + ' Manipulating database.');

    for (let i = 0; i < this.data.records.length; i++) {
      const item = this.data.records[i];

      this.client.query('SELECT pointer FROM manuscripts WHERE pointer = $1::character(5)', [item.pointer.toString()], (error, result) => {
        if (error) {
          this.logger(item.pointer, 'Incoming error.');

          throw error;
        }

        if (result.rows.length) {
          this.updateRow(item);
        } else {
          this.insertRow(item);
        }

        if (i + 1 === this.data.records.length) {
          // Initialize a two second timeout to assure any future queries
          // are completed. There might be a better way to do this.
          setTimeout(() => {
            this.closeDatabaseConnection();
          }, 2000);
        }
      });
    }
  }

  /**
   * Step 3.a - Update Fields
   *
   * Updates the database with the assumption that CONTENTdm is more accurate.
   *
   * @param {Object} item -- The manuscript from CONTENTdm.
   */
  updateRow(item) {
    this.logger(item.pointer, 'Detected a possible update.');

    let update = '';

    this.client.query('SELECT * FROM manuscripts WHERE pointer = $1::character(5)', [item.pointer.toString()], (error, result) => {
      if (error) {
        this.logger(item.pointer, 'Incoming error.');

        throw error;
      }

      this.logger(item.pointer, 'Successfully retrieved information.');

      for (let key in result.rows[0]) {
        // Assign to variable to prevent false positives from incorrect apostrophe
        // rendering or `pointer`/`parentobject` variable type matching.
        const valueRemote = item[key].toString().replace(/'/g, '&#39;').trim();

        if (result.rows[0][key] === null) {
          update += key + ' = \'' + valueRemote + '\', ';
        } else if (result.rows[0][key].toString().trim() != valueRemote) {
          update += key + ' = \'' + valueRemote + '\', ';
        }
      }

      if (update === '') {
        this.logger(item.pointer, 'Nothing to update.');

        return;
      }

      update = update.substring(0, update.length - 2);

      this.logger(item.pointer, 'Attempting to update: ' + update);

      this.client.query('UPDATE manuscripts SET ' + update + ' WHERE pointer = $1::character(5)', [item.pointer.toString()], (error, result) => {
        if (error) {
          this.logger(item.pointer, 'Incoming error.');

          throw error;
        }

        this.logger(item.pointer, 'Successfully updated information.');
      });
    });
  }

  /**
   * Step 3.b - Insert Fields
   *
   * Creates a new row in the database.
   *
   * @param {Object} item -- The manuscript from CONTENTdm.
   */
  insertRow(item) {
    this.logger(item.pointer, 'Detected an insert.');

    let insert = '';
    let values = '';

    for (let key in item) {
      // Detect the three fields not needed in the local database, and skip.
      if (['find', 'filetype', 'collection'].indexOf(key) === -1 && item[key].toString().trim() !== '') {
        insert += key + ', ';
        values += '\'' + item[key].toString().replace(/'/g, '&#39;').trim() + '\', ';
      }
    }

    insert = insert.substring(0, insert.length - 2);
    values = values.substring(0, values.length - 2);

    this.logger(item.pointer, 'Attempting to insert: ' + insert);
    this.logger(item.pointer, 'Attempting to values: ' + values);

    this.client.query('INSERT INTO manuscripts (' + insert + ') VALUES (' + values + ')', (error, result) => {
      if (error) {
        this.logger(item.pointer, 'Incoming error.');

        throw error;
      }

      this.logger(item.pointer, 'Successfully inserted information.');
    });
  }

  /**
   * Step 4 - Shutdown
   *
   * Closes the connection between this process and the database.
   */
  closeDatabaseConnection() {
    console.log(new Date() + ' Closing database connection.');

    this.client.end((error) => {
      if (error) {
        throw error;
      }
    });
  }

  /**
   * Internal logging function.
   *
   * @param {String} pointer -- The pointer of the Manuscript.
   * @param {String} text    -- The text regarding the Manuscript.
   */
  logger(pointer, text) {
    console.log(new Date() + ' (' + pointer + ') - ' + text);
  }
}

new Content();
