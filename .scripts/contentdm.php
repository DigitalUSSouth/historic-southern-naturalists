<?php
/**
 * contentdm.php
 *
 * The data miner for the `manuscripts` table within the `hsn` database.
 *
 * Note: All `pointer` and `parentobject` values will be returned as an integer.
 *       They must be converted to a string to properly work here.
 *
 * TODO: Determine what to do if there are over 1024 results returned.
 * TODO: Handle if a manuscript was deleted remotely.
 */

date_default_timezone_set("America/New_York");

class Content {
  private $content;
  private $database;

  /**
   * Step 0 - Constructor
   *
   * Initialize all class-wide variables and connect to the database.
   */
  public function __construct() {
    $this->logger("Initializing.");

    $contents = json_decode(file_get_contents("pg-connect.json"), true)["php"];

    $this->content  = "";
    $this->database = new PDO("pgsql:" . $contents["connection"], $contents["username"], $contents["password"]);

    $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * Step 1 - Retrieve Results.
   *
   * Queries a wild-card search to CONTENTdm and grabs all results.
   */
  public function retrieveResults() {
    $this->logger("Retrieving results.");

    $this->content = json_decode(file_get_contents("http://digital.tcl.sc.edu:81/dmwebservices/?q=dmQuery/hsn/CISOSEARCHALL^*^any/contri!covera!date!descri!media!publis!relati!subjec!title!transc/0/1024/0/0/0/0/0/1/json"), true);
  }

  /**
   * Step 2 - Cross-Reference with Database.
   *
   * Initially looks if the pointer is within the database and if so, updates
   * the fields to match CONTENTdm, otherwise, inserts new data.
   */
  public function manipulateDatabase() {
    $this->logger("Manipulating database.");

    foreach ($this->content["records"] as $number=>$record) {
      $prepare = $this->database->prepare("SELECT * FROM manuscripts WHERE pointer = :pointer LIMIT 1");

      $prepare->execute(array(":pointer" => strval($record["pointer"])));

      $results = (array) $prepare->fetchObject();

      // Remove the three fields not needed in the local database.
      unset($record["find"], $record["filetype"], $record["collection"]);

      // Convert pointer and parentobject to strings.
      $record["pointer"]      = strval($record["pointer"]);
      $record["parentobject"] = strval($record["parentobject"]);

      if (array_key_exists("pointer", $results)) {
        $this->logger("Updating " . $record["pointer"]);

        // Update the database.
        $this->updateDatabase($results, $record);
      } else {
        $this->logger("Inserting " . $record["pointer"]);

        // Insert into the database.
        $this->insertDatabase($record);
      }
    }
  }

  /**
   * Step 2.a - Update Fields
   *
   * Updates the database with the assumption that CONTENTdm is more accurate.
   *
   * @param {Array} $results -- The local manuscript.
   * @param {Array} $record  -- The manuscript from CONTENTdm.
   */
  private function updateDatabase($results, $record) {
    $writer = "";
    $update = array();

    foreach ($record as $key=>$value) {
      $value = trim(strval($value));

      if (array_key_exists($key, $results) && $value !== "" && $value != $results[$key]) {
        $writer .= $key . " = :" . $key . ", ";
        $update[":" . $key] = $value;
      }
    }

    if ($writer === "") {
      $this->logger("Nothing to update.");

      return;
    }

    $writer = substr($writer, 0, -2);
    $update[":pointer"] = $record["pointer"];


    $this->logger("Attempting to update " . $writer . " -- " . print_r($update, true));

    $prepare = $this->database->prepare("UPDATE manuscripts SET $writer WHERE pointer = :pointer");
    $prepare->execute($update);
  }

  /**
   * Step 2.b - Insert Fields
   *
   * Creates a new row in the database.
   *
   * @param {Object} $record -- The manuscript from CONTENTdm.
   */
  private function insertDatabase($record) {
    $array  = array();
    $insert = "";
    $values = "";

    foreach ($record as $key=>$value) {
      $value = trim(strval($value));

      if ($value !== "") {
        $insert .= $key . ", ";
        $values .= ":" . $key . ", ";

        $array[":" . $key] = $value;
      }
    }

    $insert = substr($insert, 0, -2);
    $values = substr($values, 0, -2);

    $this->logger("Query: INSERT INTO manuscripts ($insert) VALUES ($values)");
    $this->logger("Here's an " . print_r($array, true));

    $prepare = $this->database->prepare("INSERT INTO manuscripts ($insert) VALUES ($values)");
    $prepare->execute($array);
  }

  /**
   * Step 3 - Shutdown
   *
   * Closes the connection between this process and the database.
   */
  public function closeDatabaseConnection() {
    $this->database = null;
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

$content = new Content();
$content->retrieveResults();
$content->manipulateDatabase();
$content->closeDatabaseConnection();
