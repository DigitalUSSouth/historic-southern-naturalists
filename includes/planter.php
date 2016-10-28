<?php
/**
 * Plant class. Utilized only on /view-plant
 */
class Planter {
  private $id;
  private $data;

  /**
   * Constructor
   */
  public function __construct($id) {
    global $application;

    $this->id   = $id;
    $this->data = pg_fetch_all(pg_query($application->getConnection(), "SELECT * FROM plants WHERE id = '" . pg_escape_string($id) . "' LIMIT 1"))[0];
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
      ? ''
      : '<dt>' . $label . '</dt><dd>' . $this->getData($key) . '</dd>';
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
}
