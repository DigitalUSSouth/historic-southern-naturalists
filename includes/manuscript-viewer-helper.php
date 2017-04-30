<?php
/**
 * manuscript-viewer-helper.php
 *
 * Because `XMLHttpRequest`, `$.ajax`, and `$.getJSON` won't work on localhost.
 */

// Redirect if not giving the proper parameters.
if (!isset($_GET["pointer"], $_GET["collection"])) {
  header("Location: /");
}

require_once "application.php";

class Helper {
  private $pages;
  private $parent;
  private $pointer;
  private $compound;
  private $database;
  private $collection;
  private $imageWidth;
  private $imageHeight;

  /**
   * Constructor
   *
   * @param String $pointer
   *   The manuscript pointer.
   *
   * @param String $collection
   *   Collection the manuscript is in.
   */
  public function __construct($pointer, $collection) {
    global $application;

    $this->pointer    = (string) $pointer;
    $this->database   = $application->getConnection();
    $this->collection = $collection;

    $info = $this->retrieveImageInfo();

    $this->imageWidth  = $info["image_width"];
    $this->imageHeight = $info["image_height"];
  }

  /**
   * Compound Object Information
   *
   * Queries the local database for any information regarding the current
   * manuscript's parent pointer.
   *
   * This function should only be called once for the starting manuscript.
   *
   * The collection column is appended in the event pointer numbers will overlap
   * in different collections.
   *
   * @return PDOStatement Object
   */
  public function retrieveCompoundObjectInfo() {
    $prepare = $this->database->prepare("
      SELECT pointer
      FROM   manuscripts
      WHERE  collection    = :collection
        AND  parent_object = :parent_object
    ");

    $prepare->execute(array(
      ":collection"    => $this->collection,
      ":parent_object" => $this->parent
    ));

    return $prepare;
  }

  /**
   * Image Information
   *
   * Queries the local database for specific information regarding the current
   * manuscript.
   *
   * These two columns will always be needed, therefore they are in their own
   * function for optimization. It's better to return two columns rather than
   * return four columns and only needing two.
   *
   * The collection column is appended in the event pointer numbers will overlap
   * in different collections.
   *
   * @return Array
   */
  private function retrieveImageInfo() {
    $prepare = $this->database->prepare("
      SELECT image_height, image_width
      FROM   manuscripts
      WHERE  collection = :collection
        AND  pointer    = :pointer
      LIMIT  1
    ");

    $prepare->execute(array(
      ":collection" => $this->collection,
      ":pointer"    => $this->pointer
    ));

    return $prepare->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Parent Information
   *
   * Queries the local database for information regarding the parent pointer
   * for the current manuscript.
   *
   * This function should only be called once for the starting manuscript.
   *
   * The collection column is appended in the event pointer numbers will
   * overlap in different collections.
   *
   * @return String
   */
  public function retrieveParent() {
    $prepare = $this->database->prepare("
      SELECT parent_object
      FROM   manuscripts
      WHERE  collection = :collection
        AND  pointer    = :pointer
      LIMIT  1
    ");

    $prepare->execute(array(
      ":collection" => $this->collection,
      ":pointer"    => $this->pointer
    ));

    return $prepare->fetchColumn();
  }

  /**
   * Title Information
   *
   * Queries the local database for information regarding the title for the
   * current manuscript.
   *
   * This function should only be called once for the starting manuscript's
   * parent.
   *
   * The collection column is appended in the event pointer numbers will
   * overlap in different collections.
   *
   * @return String
   */
  public function retrieveTitle() {
    $prepare = $this->database->prepare("
      SELECT title
      FROM   manuscripts
      WHERE  collection = :collection
        AND  pointer    = :pointer
      LIMIT  1
    ");

    $prepare->execute(array(
      ":collection" => $this->collection,
      ":pointer"    => $this->pointer
    ));

    return $prepare->fetchColumn();
  }

  /**
   * Printer
   *
   * Prints all data necessary for js/hsn-book-reader.js
   *
   * It runs through each pointer within the compound object, grabs its
   * information, and then appends it all into an array for JavaScript to
   * deal with.
   *
   * @return Array
   */
  public function printData() {
    $parent = new Helper($this->parent, $this->collection);
    $return = array(
      "pages"  => $this->pages,
      "title"  => $parent->retrieveTitle(),
      "images" => array()
    );

    // Run through each pointer.
    for ($i = 0; $i < count($this->compound); $i++) {
      $item = $this->compound[$i];

      // If we're on the same item, do not create a new class.
      if ($item["pointer"] === $this->pointer) {
        $return["images"][$i] = array(
          "width"  => $this->getImageWidth(),
          "height" => $this->getImageHeight()
        );

        continue;
      }

      // Otherwise, create a temp class.
      $helper = new Helper($item["pointer"], $this->collection);

      $return["images"][$i] = array(
        "width"  => $helper->getImageWidth(),
        "height" => $helper->getImageHeight()
      );
    }

    return $return;
  }

  /**
   * Accessors
   */
  public function getImageHeight() {
    return $this->imageHeight;
  }

  public function getImageWidth() {
    return $this->imageWidth;
  }

  /**
   * Mutators
   */
  public function setCompound($compound) {
    $this->compound = $compound;
  }

  public function setPages($pages) {
    $this->pages = $pages;
  }

  public function setParent($parent) {
    $this->parent = $parent;
  }
}

$helper = new Helper($_GET["pointer"], $_GET["collection"]);
$helper->setParent($helper->retrieveParent());

$compound = $helper->retrieveCompoundObjectInfo();

$helper->setPages($compound->rowCount());
$helper->setCompound($compound->fetchAll());

print json_encode($helper->printData());
