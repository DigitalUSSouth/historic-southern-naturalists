<?php
/**
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

    $info = json_decode(file_get_contents("http://digital.tcl.sc.edu/utils/ajaxhelper/?CISOROOT=hsn&CISOPTR=" . $pointer), true)["imageinfo"];

    $this->data    = pg_fetch_all(pg_query($application->getConnection(), "SELECT * FROM manuscripts WHERE pointer = '" . pg_escape_string($pointer) . "' LIMIT 1"))[0];
    $this->image   = "http://digital.tcl.sc.edu/utils/ajaxhelper/?action=2&CISOROOT=hsnCISOPTR=" . $pointer . "&DMWIDTH=" . $info["width"] . "&DMHEIGHT=" . $info["height"];
    $this->pointer = $pointer;
  }

  /**
   * Determines if the data requested does have a value. If so, render it.
   *
   * @param  String $label -- The label of the data.
   * @param  String $key   -- The data key for the database.
   * @return String
   */
  public function renderData($label, $key) {
    return trim($this->data[$key]) === ""
      ? ""
      : "<dt>" . $label . "</dt><dd>" . $this->data[$key] . "</dd>";
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
