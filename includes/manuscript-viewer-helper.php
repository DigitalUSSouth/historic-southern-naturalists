<?php
/**
 * manuscript-viewer-helper.php
 *
 * Because XMLHttpRequest, $.ajax, and $.getJSON won't work on localhost.
 */

if (!isset($_GET["pointer"])) {
  header("Location: /");
}

class Helper {
  private $info;
  private $title;
  private $parent;
  private $pointer;
  private $compound;
  private $imageInfo;

  /**
   * Constructor
   *
   * @param Integer $pointer -- The Manuscript pointer.
   */
  public function __construct($pointer) {
    $this->pointer = $pointer;

    $this->info      = $this->acquireItemInfo();
    $this->title     = $this->info["title"];
    $this->parent    = $this->acquireParent()["parent"];
    $this->compound  = $this->acquireCompoundObjectInfo();
    $this->imageInfo = $this->acquireImageInfo();
  }

  /**
   * Image Info
   *
   * Acquires the information regarding the width and height of the image.
   *
   * @return Array
   */
  private function acquireImageInfo() {
    return json_decode(file_get_contents("http://digital.tcl.sc.edu/utils/ajaxhelper/?CISOROOT=hsn&CISOPTR=" . $this->pointer), true);
  }

  /**
   * Item Info
   *
   * Acquires the information regarding everything about the item.
   *
   * @return Array
   */
  private function acquireItemInfo() {
    return json_decode(file_get_contents($this->constructPortURL("dmGetItemInfo")), true);
  }

  /**
   * Parent
   *
   * Acquires the information regarding the parent of the item.
   *
   * @return Array
   */
  private function acquireParent() {
    return json_decode(file_get_contents($this->constructPortURL("GetParent")), true);
  }

  /**
   * Compound Object
   *
   * Acquires the information regarding the compound object this is part of.
   *
   * @return Array
   */
  private function acquireCompoundObjectInfo() {
    return json_decode(file_get_contents($this->constructPortURL("dmGetCompoundObjectInfo")), true);
  }

  /**
   * URL Constructor
   *
   * Constructs a URL for CONTENTdm with a given parameter, centering around
   * the item pointer.
   *
   * @param  String $parameter -- The API query.
   * @return String
   */
  private function constructPortURL($parameter) {
    return "http://digital.tcl.sc.edu:81/dmwebservices/?q=" . $parameter . "/hsn/" . $this->pointer . "/json";
  }

  /**
   * Printer
   *
   * Prints all data necessary for js/hsn-book-reader.js
   *
   * @return Array
   */
  public function printData() {
    $index  = -1;
    $parent = new Helper($this->parent);
    $return = array(
      "pages"  => count($parent->getCompound()["page"]),
      "title"  => $parent->getInfo()["title"],
      "images" => array()
    );

    // Run through all compound object items.
    foreach ($parent->getCompound()["page"] as $object) {
      $index++;

      // If we're on the same item, do not create a new class.
      if ($object["pageptr"] === $this->pointer) {
        $return["images"][$index] = array(
          "width"  => $this->imageInfo["imageinfo"]["width"],
          "height" => $this->imageInfo["imageinfo"]["height"]
        );

        continue;
      }

      // Create a temporary class.
      $helper = new Helper($object["pageptr"]);

      $return["images"][$index] = array(
        "width"  => $helper->getImageInfo()["imageinfo"]["width"],
        "height" => $helper->getImageInfo()["imageinfo"]["height"]
      );
    }

    return $return;
  }

  /**
   * Accessors
   */
  public function getCompound() {
    return $this->compound;
  }

  public function getImageInfo() {
    return $this->imageInfo;
  }

  public function getInfo() {
    return $this->info;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getParent() {
    return $this->parent;
  }

  public function getPointer() {
    return $this->pointer;
  }

  /**
   * Mutators
   */
  public function setCompound($compound) {
    $this->compound = $compound;
  }

  public function setImageInfo($imageInfo) {
    $this->imageInfo = $imageInfo;
  }

  public function setInfo($info) {
    $this->info = $info;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function setParent($parent) {
    $this->parent = $parent;
  }

  public function setPointer($pointer) {
    $this->pointer = $pointer;
  }
}

$helper = new Helper($_GET["pointer"]);

print json_encode($helper->printData());
