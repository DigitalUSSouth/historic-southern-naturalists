<?php
/**
 * searcher.php
 *
 * The behind-the-scenes revolving around `/search`
 */
class Searcher {
  private $search;
  private $plantKeys;
  private $connection;
  private $contentKeys;

  /**
   * Constructor
   *
   * @param String $search
   *   Search query.
   */
  public function __construct($search) {
    global $application;

    $this->search     = $search;
    $this->connection = $application->getConnection();

    $this->plantKeys = array(
      "county",
      "country",
      "event_date",
      "family",
      "genus",
      "habitat",
      "identified_by",
      "locality",
      "recorded_by",
      "scientific_name",
      "state_province"
    );

    $this->contentKeys = array(
      "contri",
      "covera",
      "date",
      "descri",
      "publis",
      "relati",
      "subjec",
      "title"
    );
  }

  /**
   * Table Renderer
   *
   * With a given type, determine what type of table needs to be rendered on the
   * search page.
   *
   * All possible types are located at the top of the search page.
   *
   * @param String $type
   *   The table type.
   *
   * @return String
   */
  public function renderTable($type) {
    if ($type === "plants") {
      return $this->renderTablePlants();
    } else {
      return $this->renderContentTable($type);
    }
  }

  /**
   * CONTENTdm Table Renderer
   *
   * Queries the database for any data that would matter coming from the
   * `manuscripts` table.
   *
   * All manuscript-type data will return the same headers:
   * - Thumbnail
   * - Title
   * - Description
   *
   * @param String $type
   *   The media type.
   *
   * @return String
   */
  private function renderContentTable($type) {
    global $application;

    $html  = "";
    $media = "";

    if ($type === "manuscripts") {
      $media = "media LIKE '%Manuscripts'";
    } else {
      $media = "media ILIKE '" . $type . "'";
    }

    $prepare = $this->connection->prepare("
      SELECT descri, media, pointer, title
      FROM   manuscripts
      WHERE  (" . $this->renderQuery("content") . ")
        AND  $media
    ");

    $prepare->execute($this->populateArray("content"));

    foreach ($prepare->fetchAll() as $key=>$result) {
      $html .= "<tr>";

      // Render the thumbnail with a link.
      $html .= "<td>"
        . "<a href=\"" . $application->getURL() . "view-content.php?pointer=" . $result["pointer"] . "\">"
        . "<img src=\"" . $application->buildManuscriptThumbURL($result["pointer"]) . "\" class=\"img-responsive\" alt=\"Thumbnail of " . $result["title"] . "\">"
        . "</a>"
        . "</td>";

      // Render the title with a link.
      $html .= "<td>"
        . "<a href=\"" . $application->getURL() . "view-content.php?pointer=" . $result["pointer"] . "\">" . $result["title"] . "</a>"
        . "</td>";

      // Render the description.
      $html .= "<td>" . $result["descri"] . "</td>";

      $html .= "</tr>";
    }

    return $html;
  }

  /**
   * Plant Table Renderer
   *
   * Queries the database for all possible data related to any data coming from
   * Symbiota in a mirror-based `plants` table.
   *
   * @return String
   */
  private function renderTablePlants() {
    global $application;

    $html = "";

    $prepare = $this->connection->prepare("
      SELECT id, scientific_name, habitat
      FROM   plants
      WHERE  (" . $this->renderQuery("plants") . ")
        AND  scientific_name != ''
    ");

    $prepare->execute($this->populateArray("plants"));

    foreach ($prepare->fetchAll() as $current=>$result) {
      $html .= "<tr>";

      // Render the thumbnail with a link.
      // TODO: Retrieve Symbiota thumbnail URL once its ready.
      $html .= "<td>"
        . "<a href=\"" . $application->getURL() . "view-plant.php?id=" . $result["id"] . "\">"
        . "<img src=\"#\" class=\"img-responsive\" alt=\"Thumbnail of " . $result["scientific_name"] . "\">"
        . "</a>"
        . "</td>";

      // Render the scientific name with a link.
      $html .= "<td>"
        . "<a href=\"" . $application->getURL() . "view-plant.php?id=" . $result["id"] . "\">" . $result["scientific_name"] . "</a>"
        . "</td>";

      // Render the habitat plainly.
      $html .= "<td>" . $result["habitat"] . "</td>";

      $html .= "</tr>";
    }

    return $html;
  }

  /**
   * PDO Prepared Array
   *
   * Populates an array for PDO prepared statements for the given type and its
   * respective results.
   *
   * All keys are static and declared within the constructor.
   *
   * @param String $type
   *   Table type.
   *
   * @return Array
   */
  private function populateArray($type) {
    $keys  = array();
    $array = array();

    if ($type === 'plants') {
      $keys = $this->plantKeys;
    } else {
      $keys = $this->contentKeys;
    }

    foreach ($keys as $key) {
      $array[":" . $key] = "%" . $this->search . "%";
    }

    return $array;
  }

  /**
   * PDO Prepared Query
   *
   * Renders a query for PDO prepared statements for the given type and its
   * respective results.
   *
   * All keys are static and declared within the constructor.
   *
   * @param String $type
   *   Table type.
   *
   * @return String
   */
  private function renderQuery($type) {
    $query = "";

    if ($type === 'plants') {
      $keys = $this->plantKeys;
    } else {
      $keys = $this->contentKeys;
    }

    foreach ($keys as $key) {
      $query .= $key . " ILIKE :" . $key . " OR ";
    }

    return substr($query, 0, -4);
  }

  /**
   * Accessors
   */
  public function getSearch() {
    return $this->search;
  }
}
