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
   * Data Exploder
   *
   * Explodes the given data based on the semicolons.
   *
   * In CONTENTdm's data, it is assumed that semicolons means a new entry, or
   * line break, of new data within the same field.
   *
   * @param  String $data -- Data comprised of at least one semicolon.
   * @return String
   */
  private function explodeData($data) {
    // Return if there are no semicolons discovered.
    if (strpos($data, ";") === false) {
      return $data;
    }

    $list = "";

    foreach (explode(";", $data) as $item) {
      $list .= "<li>" . $item . "</li>";
    }

    return "<ul>" . $list . "</ul>";
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
   * Basic Data Renderer
   *
   * Renders the data either in a paragraph format or a listed format based
   * on whether the data has semicolons within the text.
   *
   * @param  String $key -- The data key for the database.
   * @return String
   */
  public function renderBasicData($key) {
    if (strpos($this->data[$key], ";") !== false) {
      return $this->explodeData($this->data[$key]);
    } else {
      return "<p class=\"text-justify\">" . $this->data[$key] . "</p>";
    }
  }

  /**
   * Detail List Data Renderer
   *
   * Renders the data within the detail section. If applicable, render the data
   * within a list.
   *
   * @param  String $label -- The human-readable label of the data.
   * @param  String $key   -- The data key for the database.
   * @return String
   */
  public function renderDetailData($label, $key) {
    if (trim($this->data[$key]) === "") {
      return "";
    } else {
      return "<dt>" . $label . "</dt><dd>" . $this->explodeData($this->data[$key]) . "</dd>";
    }
  }

  /**
   * Accessors
   */
  public function getData($key) {
    return $this->data[$key];
  }

  public function getImage() {
    return $this->image;
  }
}
