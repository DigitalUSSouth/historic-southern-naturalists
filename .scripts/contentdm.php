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

    $this->content  = "";
    $this->database = pg_connect(pg_connect(json_decode(file_get_contents("pg-connect.json"), true)["php"]));
  }

  /**
   * Step 1 - Retrieve Results.
   *
   * Queries a wild-card search to CONTENTdm and grabs all results.
   */
  public function retrieveResults() {
    $this->logger("Retrieving results.");

    $this->content = json_decode(file_get_contents("http://digital.tcl.sc.edu:81/dmwebservices/?q=dmQuery/hsn/CISOSEARCHALL^*^any/contri!covera!date!descri!publis!relati!subjec!title!transc/0/1024/0/0/0/0/0/1/json"), true);
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
      $result = pg_fetch_result(pg_query_params($this->database, "SELECT pointer FROM manuscripts WHERE pointer = $1", array(strval($record["pointer"]))), 0);

      // Remove the three fields not needed in the local database.
      unset($record["find"], $record["filetype"], $record["collection"]);

      // Convert pointer and parentobject to strings.
      $record["pointer"]      = strval($record["pointer"]);
      $record["parentobject"] = strval($record["parentobject"]);

      if ($result) {
        $this->logger("Updating " . $record["pointer"]);

        // Update the database.
        if (pg_update($this->database, "manuscripts", $record, $record) === false) {
          throw new Exception("Updating manuscript " . $record["pointer"] . " failed.");
        }
      } else {
        $this->logger("Inserting " . $record["pointer"]);

        // Insert into the database.
        if (pg_insert($this->database, "manuscripts", $record) === false) {
          throw new Exception("Inserting manuscript " . $record["pointer"] . " failed.");
        }
      }
    }
  }

  /**
   * Step 3 - Shutdown
   *
   * Closes the connection between this process and the database.
   */
  public function closeDatabaseConnection() {
    pg_close($this->connection);
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
