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

  public function __construct($pointer) {
    $this->pointer = $pointer;

    $this->info      = $this->acquireItemInfo();
    $this->title     = $this->info["title"];
    $this->parent    = $this->acquireParent()["parent"];
    $this->compound  = $this->acquireCompoundObjectInfo();
    $this->imageInfo = $this->acquireImageInfo();
  }

  private function acquireImageInfo() {
    return json_decode(file_get_contents("http://digital.tcl.sc.edu/utils/ajaxhelper/?CISOROOT=hsn&CISOPTR=" . $this->pointer), true);
  }

  private function acquireItemInfo() {
    return json_decode(file_get_contents($this->constructPortURL("dmGetItemInfo")), true);
  }

  private function acquireParent() {
    return json_decode(file_get_contents($this->constructPortURL("GetParent")), true);
  }

  private function acquireCompoundObjectInfo() {
    return json_decode(file_get_contents($this->constructPortURL("dmGetCompoundObjectInfo")), true);
  }

  private function constructPortURL($parameter) {
    return "http://digital.tcl.sc.edu:81/dmwebservices/?q=" . $parameter . "/hsn/" . $this->pointer . "/json";
  }

  public function printData() {
    $parent = new Helper($this->parent);
    $return = array(
      "pages"  => count($parent->getCompound()["page"]),
      "title"  => $parent->getInfo()["title"],
      "images" => array()
    );

    foreach ($parent->getCompound()["page"] as $object) {
      if ($object["pageptr"] === $this->pointer) {
        $return["images"][$this->pointer] = array(
          "width"  => $this->imageInfo["imageinfo"]["width"],
          "height" => $this->imageInfo["imageinfo"]["height"]
        );

        continue;
      }

      $helper = new Helper($object["pageptr"]);

      $return["images"][$helper->getPointer()] = array(
        "width"  => $this->imageInfo["imageinfo"]["width"],
        "height" => $this->imageInfo["imageinfo"]["height"]
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

