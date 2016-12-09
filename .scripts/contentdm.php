<?php
/**
 * contentdm.php
 *
 * The data miner for the `manuscripts` table within the `hsn` database.
 *
 * Note: All `pointer` and `parent_object` values will be returned as an
 *       integer. They must be converted to a string to properly work here.
 *
 * TODO: Determine what to do if there are over 1024 results returned.
 * TODO: Handle if a manuscript was deleted remotely.
 */

date_default_timezone_set("America/New_York");

class Content {
  private $content;
  private $database;
  private $collection;

  /**
   * Step 0 - Constructor
   *
   * Initialize all class-wide variables and connect to the database.
   */
  public function __construct($collection) {
    $this->logger("Initializing.");

    $contents = json_decode(file_get_contents("pg-connect.json"), true)["php"];

    $this->content    = "";
    $this->database   = new PDO("pgsql:" . $contents["connection"], $contents["username"], $contents["password"]);
    $this->collection = $collection;

    $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * Step 1 - Retrieve Results.
   *
   * Queries a wild-card search to CONTENTdm and grabs all results.
   */
  public function retrieveResults() {
    $this->logger("Retrieving results.");

    $results = array();

    if ($this->collection === "kmc") {
      $results = array("date", "title");
    } else {
      $results = array("contri", "covera", "date", "descri", "media", "publis", "relati", "subjec", "title", "transc");
    }

    $this->content = json_decode(file_get_contents("http://digital.tcl.sc.edu:81/dmwebservices/?q=dmQuery/" . $this->collection . "/CISOSEARCHALL^*^any/" . implode("!", $results) . "/0/1024/0/0/0/0/0/1/json"), true);
  }

  /**
   * Step 2 - Cross-Reference with Database.
   *
   * Initially looks if the pointer is within the database and if so, updates
   * the fields to match CONTENTdm, otherwise, inserts new data.
   */
  public function manipulateDatabase() {
    $this->logger("Manipulating database.");

    foreach ($this->content["records"] as $key=>$record) {
      // Convert pointer to a string.
      $record["pointer"] = trim((string) $record["pointer"]);

      $prepare = $this->database->prepare("SELECT * FROM manuscripts WHERE pointer = :pointer LIMIT 1");
      $prepare->execute(array(":pointer" => $record["pointer"]));

      $results = (array) $prepare->fetchObject();

      // Trim collection.
      $record["collection"] = trim(substr($record["collection"], 1));

      // Convert to parent_object.
      $record["parent_object"] = trim((string) $record["parentobject"]);

      // Remove the fields not needed in the local database.
      unset($record["find"], $record["filetype"], $record["parentobject"]);

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
   * Step 2.1 - Update Fields
   *
   * Updates the database with the assumption that CONTENTdm is more accurate.
   *
   * @param Array $results -- The local manuscript.
   * @param Array $record  -- The manuscript from CONTENTdm.
   */
  private function updateDatabase($results, $record) {
    $writer = "";
    $update = array(":pointer" => $record["pointer"]);

    foreach ($record as $key=>$value) {
      // Assure value is a string and trimmed.
      $value = trim((string) $value);

      // No point in populating empty or same data.
      if ($value === "" || $value === $results[$key]) {
        continue;
      }

      $writer .= $key . " = :" . $key . ", ";
      $update[":" . $key] = $value;
    }

    $update = array_merge($update, $this->retrieveCompoundPage($record));
    $update = array_merge($update, $this->retrieveImageDimensions($record));
    $update = array_merge($update, $this->determineCompoundObject($record["pointer"]));

    $writer .= "compound_page = :compound_page, image_height = :image_height, image_width = :image_width, is_compound_object = :is_compound_object";

    $this->logger("Query: UPDATE manuscripts SET (" . $writer . ") WHERE pointer = " . $record["pointer"]);
    $this->logger(print_r($update, true));

    $prepare = $this->database->prepare("UPDATE manuscripts SET $writer WHERE pointer = :pointer");
    $prepare->execute($update);
  }

  /**
   * Step 2.2 - Insert Fields
   *
   * Creates a new row in the database.
   *
   * @param Array $record -- The manuscript from CONTENTdm.
   */
  private function insertDatabase($record) {
    $array  = array();
    $insert = "";
    $values = "";

    foreach ($record as $key=>$value) {
      // Assure value is a string and trimmed.
      $value = trim((string) $value);

      // No point in populating empty data.
      if ($value === "") {
        continue;
      }

      $insert .= $key . ", ";
      $values .= ":" . $key . ", ";

      $array[":" . $key] = $value;
    }

    $array = array_merge($array, $this->retrieveCompoundPage($record));
    $array = array_merge($array, $this->retrieveImageDimensions($record));
    $array = array_merge($array, $this->determineCompoundObject($record["pointer"]));

    $insert .= "compound_page, image_height, image_width, is_compound_object";
    $values .= ":compound_page, :image_height, :image_width, :is_compound_object";

    $this->logger("Query: INSERT INTO manuscripts ($insert) VALUES ($values)");
    $this->logger(print_r($array, true));

    $prepare = $this->database->prepare("INSERT INTO manuscripts ($insert) VALUES ($values)");
    $prepare->execute($array);
  }

  /**
   * Step 2.x.1 - Compound Object Page Retriever
   *
   * Determines what page the given record is within its compound object
   * information. This will start numbering at 0.
   *
   * Returning a -1 indicates that the item itself is a compound object, and
   * therefore does not have a page, or it is not a compound object. Either
   * way, the manuscript does not have page number.
   *
   * @param  Array $record -- The manuscript.
   * @return Array
   */
  private function retrieveCompoundPage($record) {
    $pointer = $record["pointer"];

    $this->logger("Retrieving the page location for " . $pointer);

    $remote = json_decode(file_get_contents($this->constructParameterURL("dmGetCompoundObjectInfo", $pointer)), true);

    if (array_key_exists("message", $remote)) {
      $parent = $record["parent_object"];

      $remote = json_decode(file_get_contents($this->constructParameterURL("dmGetCompoundObjectInfo", $parent)), true);
    } else {
      // Assume it is the compound object.
      return array(":compound_page" => (string) -1);
    }

    // Assume it is not a compound object at all.
    if (!array_key_exists("page", $remote)) {
      return array(":compound_page" => (string) -1);
    }

    foreach ($remote["page"] as $key=>$page) {
      if ($page["pageptr"] === $pointer) {
        return array(":compound_page" => (string) $key);
      }
    }
  }

  /**
   * Step 2.x.2 - Image Dimension Retriever
   *
   * Determines the dimensions of the big image specifically for the given
   * record. This is done due to each image being different size, and will
   * reduce bandwidth stress between servers.
   *
   * @param  Array $record -- Manuscript
   * @return Array
   */
  private function retrieveImageDimensions($record) {
    $this->logger("Retrieving image dimensions for " . $record["pointer"]);

    $remote = json_decode(file_get_contents("http://digital.tcl.sc.edu/utils/ajaxhelper/?CISOROOT=" . $record["collection"] . "&CISOPTR=" . $record["pointer"]), true);

    return array(":image_height" => (string) $remote["imageinfo"]["height"], ":image_width" => (string) $remote["imageinfo"]["width"]);
  }

  /**
   * Step 2.x.3 - Compound Object Determiner
   *
   * Determines if the given pointer is a compound object based on the
   * CONTENTdm API call of `dmGetCompoundObjectInfo`.
   *
   * @param  String $pointer -- Manuscript pointer.
   * @return Array
   */
  private function determineCompoundObject($pointer) {
    $this->logger("Retrieving compound object information.");

    $remote = json_decode(file_get_contents($this->constructParameterURL("dmGetCompoundObjectInfo", $pointer)), true);

    // Note: Returning just `true` and `false` will throw a PDOException with
    //       an `invalid input syntax` response. I don't know why either, but
    //       this makes everyone happy.
    if (array_key_exists("message", $remote)) {
      return array(":is_compound_object" => intval(false));
    } else {
      return array(":is_compound_object" => intval(true));
    }
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
   * Internal String Constructor
   *
   * Constructs a string URL based on the given CONTNETdm API parameter and
   * manuscript pointer.
   *
   * @param  String $parameter -- CONTENTdm API call.
   * @param  String $pointer   -- Manuscript pointer.
   * @return String
   */
  private function constructParameterURL($parameter, $pointer) {
    return "http://digital.tcl.sc.edu:81/dmwebservices/?q=" . $parameter . "/" . $this->collection . "/" . $pointer . "/json";
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

foreach (array("kmc", "hsn") as $collection) {
  $content = new Content($collection);
  $content->retrieveResults();
  $content->manipulateDatabase();
  $content->closeDatabaseConnection();
}
