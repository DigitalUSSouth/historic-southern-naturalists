<?php
/**
 * symbiota.php
 *
 * The data miner for the `plants` table within the `hsn` database.
 *
 * TODO: Handle if a plant was deleted remotely.
 */

date_default_timezone_set("America/New_York");

class Symbiota {
  private $data;
  private $database;

  /**
   * Step 0 - Constructor
   *
   * Initialize all class-wide variables, connect to the database, and delete
   * (if applicable) the previous temporary folder.
   */
  public function __construct() {
    $this->logger("Initializing.");

    $this->data     = array();
    $this->database = pg_connect(json_decode(file_get_contents("pg-connect.json"), true)["php"]);

    // Delete the tmp folder in the case that overwriting goes wrong.
    if (in_array("tmp", scandir(getcwd()))) {
      $this->deleteFolder("./tmp/");
    }
  }

  /**
   * Step 1 - Retrieve Results.
   *
   * Connects to a public-facing RSS feed that returns all information about
   * Symbiota in a CSV format.
   */
  public function retrieveResults() {
    $this->logger("Retrieving results.");

    file_put_contents("archive.zip", fopen("http://herbarium.biol.sc.edu/floracaroliniana/webservices/dwc/dwcapubhandler.php?collid=1&cond=collectioncode-equals:ACM", 'r'));

    $this->logger("Results received.");

    // Unzip the archive.
    $zip = new ZipArchive;

    if ($zip->open('archive.zip') === TRUE) {
      $this->logger("Unzipping archive.");

      $zip->extractTo('./tmp/');
      $zip->close();

      // Delete the archive.
      unlink('./archive.zip');
    } else {
      throw new Exception("The archive does not exist.");
    }
  }

  /**
   * Step 2 - Result Parser.
   *
   * Parse the results from CSV to a PHP-acceptable array. Since PapaParse's
   * headers option is not an option here, remap the array to have proper keys.
   */
  public function parseResults() {
    $this->logger("Parsing results.");

    // Convert from CSV to an array.
    $convert = array_map("str_getcsv", explode("\n", file_get_contents("./tmp/occurrences.csv")));

    // The first array is the headers, all other are data.
    $headers = $convert[0];

    // Remove the headers.
    unset($convert[0]);

    // Run through the initial converted array and remap it.
    foreach ($convert as $record=>$plant) {
      $array = array();

      foreach ($plant as $key=>$value) {
        switch ($key) {
          case 0:  // id
          case 13: // family
          case 16: // genus
          case 37: // habitat
          case 51: // country
          case 53: // county
          case 55: // locality
            $array[$headers[$key]] = $value;
            break;

          // scientificName => scientific_name
          case 14:
            $array["scientific_name"] = $value;
            break;

          // identifiedBy => identified_by
          case 20:
            $array["identified_by"] = $value;
            break;

          // recordedBy => recorded_by
          case 27:
            $array["recorded_by"] = $value;
            break;

          // eventDate => event_date
          case 29:
            $array["event_date"] = $value;
            break;

          // stateProvince => state_province
          case 52:
            $array["state_province"] = $value;
            break;

          // decimalLatitude => decimal_latitude
          case 57:
            $array["decimal_latitude"] = $value;
            break;

          // decimalLongitude => decimal_longitude
          case 58:
            $array["decimal_longitude"] = $value;
            break;
        }
      }

      array_push($this->data, $array);
    }
  }

  /**
   * Step 3 - Cross-Reference with Database.
   *
   * Initially looks if the id is within the database, and if so, updates the
   * fields to match Symbiota, otherwise, insert new data.
   */
  public function manipulateDatabase() {
    $this->logger("Manipulating database.");

    foreach ($this->data as $number=>$record) {
      $result = pg_fetch_result(pg_query_params($this->database, "SELECT id FROM plants WHERE id = $1", array($record["id"])), 0);

      if ($result) {
        $this->logger("Updating " . $record["id"]);

        // Update the database.
        if (pg_update($this->database, "plants", $record, $record) === false) {
          throw new Exception("Updating plant " . $record["id"] . " failed.");
        }
      } else {
        $this->logger("Inserting " . $record["id"]);

        // Insert into the database.
        if (pg_insert($this->database, "plants", $record) === false) {
          throw new Exception("Inserting plant " . $record["id"] . " failed.");
        }
      }
    }
  }

  /**
   * Step 4 - Shutdown
   *
   * Closes the connection between this process and the database and then
   * removes the temporary folder.
   */
  public function closeDatabaseConnection() {
    pg_close($this->database);

    // There is no point of keeping the tmp folder here now.
    if (in_array("tmp", scandir(getcwd()))) {
      $this->deleteFolder("./tmp/");
    }
  }

  /**
   * Internal recursive function to delete all contents within a folder
   * before removing the folder itself.
   *
   * @param String $path -- The folder to delete.
   */
  private function deleteFolder($path) {
    // Check if we're actually a directory.
    if (!is_dir($path)) {
      throw new InvalidArgumentException("$path must be a directory.");
    }

    // Check if we have a trailing slash.
    if (substr($path, strlen($path) - 1, 1) !== "/") {
      $path .= "/";
    }

    // Run through each entity and either delete the file or restart
    // folder deletion process.
    foreach (scandir($path) as $count=>$file) {
      if ($file === "." || $file === "..") {
        continue;
      }

      if (is_dir($file)) {
        $this->deleteFolder($file);
      } else {
        $this->logger("Deleting file: " . $path . $file);
        unlink($path . $file);
      }
    }

    rmdir($path);
  }

  /**
   * Internal logging function.
   *
   * @param String $text -- The text to log.
   */
  private function logger($text) {
    print date("r") . " " . $text . "\n";
  }
}

$symbiota = new Symbiota();
$symbiota->retrieveResults();
$symbiota->parseResults();
$symbiota->manipulateDatabase();
$symbiota->closeDatabaseConnection();
