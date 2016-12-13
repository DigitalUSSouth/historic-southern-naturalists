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

    $prepare = $application->getConnection()->prepare("SELECT * FROM plants WHERE id = :id LIMIT 1");
    $prepare->execute(array(":id" => $id));

    $this->id   = $id;
    $this->data = (array) $prepare->fetchObject();
  }

  /**
   * Data Determiner
   *
   * Determines if the current plant has data within the given key.
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
