class Symbiota {
  constructor() {
    // Imports.
    this.fs       = require('fs');
    this.papa     = require('../imports/library/papaparse.js');
    this.path     = require('path');
    this.download = require('download');

    // CSV file reading input from.
    this.csv = 'occurrences.csv';

    // URL to download zip archive.
    this.url = 'http://herbarium.biol.sc.edu/floracaroliniana/webservices/dwc/dwcapubhandler.php?collid=1&cond=collectioncode-equals:ACM';

    // Output file that Meteor will read from.
    this.file = '../private/symbiota-data.json';

    // Folder all this stuff is happening in.
    this.folder = './tmp/';
  }

  prepare() {
    this.deleteFolder(this.folder);
  }

  /**
   * Recursive functionality to delete folder and its contents.
   *
   * @param {String} path - Folder path in UNIX style.
   */
  deleteFolder(path) {
    if (this.fs.existsSync(path)) {
      this.fs.readdirSync(path).forEach((file, index) => {
        const current = path + '/' + file;

        if (this.fs.lstatSync(current).isDirectory()) {
          deleteFolder(current);
        } else {
          this.fs.unlinkSync(current);
        }
      });

      this.fs.rmdirSync(path);
    }
  }

  /**
   * Downloads a file from a given URL and then extracts its contents.
   *
   * Sends off the signal to read in the contents of the extracted files.
   */
  retrieve() {
    this.download(this.url, this.folder, { extract: true }).then(() => {
      this.read();
    });
  }

  /**
   * Reads in new data from a given UNIX-style file path.
   *
   * Sends off the signal to parse the data that was just read.
   */
  read() {
    this.fs.readFile(this.folder + this.csv, 'utf-8', (error, data) => {
      if (error) {
        throw error;
      }

      this.parse(data);
    })
  }

  /**
   * Utilizing PapaParse, parse the CSV data into JSON format.
   *
   * Sends off the signal to write data from PapaParse.
   *
   * @see   http://papaparse.com/docs#config
   * @param {CSV} data - CSV contents to be parsed into JSON.
   */
  parse(data) {
    const results = this.papa.parse(data, {
      header: true,

      error(error) {
        console.log('Error:', error);
      }
    });

    this.write(results);
  }

  /**
   * Extracts useful data from PapaParse and writes the contents
   * to an external file for Meteor to deal with later.
   *
   * @param {Object} results - The results object from PapaParse
   */
  write(results) {
    this.fs.writeFile(this.path.resolve(__dirname, this.file), JSON.stringify(results.data), (error) => {
      if (error) {
        throw error;
      }
    });
  }
};

const miner = new Symbiota();

miner.prepare();

// After this, it's all just callbacks.
miner.retrieve();
