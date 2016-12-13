<?php
/**
 * content.php
 *
 * Content class. Utilized only on /view-content
 */
class Content {
  private $data;
  private $image;
  private $pointer;

  /**
   * Constructor
   *
   * @param String $pointer -- The pointer of the manuscript.
   */
  public function __construct($pointer) {
    global $application;

    $info    = json_decode(file_get_contents("http://digital.tcl.sc.edu/utils/ajaxhelper/?CISOROOT=hsn&CISOPTR=" . $pointer), true)["imageinfo"];
    $prepare = $application->getConnection()->prepare("
      SELECT *
      FROM   manuscripts
      WHERE  pointer = :pointer
      LIMIT  1
    ");

    $prepare->execute(array(":pointer" => $pointer));

    $this->data    = (array) $prepare->fetchObject();
    $this->image   = $application->buildManuscriptImageURL($pointer, $info);
    $this->pointer = $pointer;
  }

  /**
   * Data Determiner
   *
   * Determines if the current manuscript has data within the given key.
   *
   * @param  String  $key -- Key for the database.
   * @return Boolean
   */
  public function hasData($key) {
    return trim($this->data[$key]) !== "";
  }

  /**
   * Determines if the data requested does have a value. If so, render it.
   *
   * @param  String $label -- The label of the data.
   * @param  String $key   -- The data key for the database.
   * @return String
   */
  public function renderData($label, $key) {
    if (trim($this->data[$key]) === "") {
      return "";
    } else {
      return "<dt>" . $label . "</dt><dd>" . $this->data[$key] . "</dd>";
    }
  }

  /**
   * Same concept as any other accessor only this is a more specific
   * request, given by the website.
   *
   * @param  String $key -- The type of data being requested.
   * @return String
   */
  public function getData($key) {
    return $this->data[$key];
  }

  /**
   * Accessors.
   */
  public function getImage() {
    return $this->image;
  }
}
