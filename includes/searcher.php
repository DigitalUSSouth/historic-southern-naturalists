<?php
/**
 * The behind-the-scenes revolving around `/search`
 */
class Searcher {
  private $search;
  private $plantKeys;
  private $connection;
  private $contentKeys;

  /**
   * Constructor
   */
  public function __construct() {
    global $application;

    $this->search      = "";
    $this->plantKeys   = array("county", "country", "event_date", "family", "genus", "habitat", "identified_by", "locality", "recorded_by", "scientific_name", "state_province");
    $this->connection  = $application->getConnection();
    $this->contentKeys = array("contri", "covera", "date", "descri", "publis", "relati", "subjec", "title");
  }

  /**
   * Queries the database for the search term and returns the interior of a
   * `<tbody>` with the proper results.
   *
   * @todo React to empty results.
   *
   * @return String
   */
  public function renderTableManuscripts() {
    global $application;

    $html  = "";
    $query = $this->connection->prepare("SELECT pointer, title, contri, media FROM manuscripts WHERE (" . $this->renderContentQuery() . ") AND media != 'Minerals'");

    $query->execute($this->populateContentArray());

    foreach ($query->fetchAll() as $current=>$result) {
      $html .= '<tr>';

      // Render the thumbnail with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'view-content.php?pointer=' . $result["pointer"] . '">'
        . '<img src="' . $application->buildManuscriptThumbURL($result["pointer"]) . '" class="img-responsive" alt="Thumbnail of ' . $result["title"] . '">'
        . '</a>'
        . '</td>';

      // Render the title with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'view-content.php?pointer=' . $result["pointer"] . '">' . $result["title"] . '</a>'
        . '</td>';

      // Render the contributor plainly.
      $html .= '<td>' . $result["contri"] . '</td>';

      $html .= '</tr>';
    }

    return $html;
  }

  /**
   * Queries the database for the search term and returns the interior of a
   * `<tbody>` with the proper results.
   *
   * @todo React to empty results.
   *
   * @return String
   */
  public function renderTableMinerals() {
    global $application;

    $html  = "";
    $query = $this->connection->prepare("SELECT pointer, title, descri, media FROM manuscripts WHERE (" . $this->renderContentQuery() . ") AND media = 'Minerals'");

    $query->execute($this->populateContentArray());

    foreach ($query->fetchAll() as $current=>$result) {
      $html .= '<tr>';

      // Render the thumbnail with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'view-content.php?pointer=' . $result["pointer"] . '">'
        . '<img src="' . $application->buildManuscriptThumbURL($result["pointer"]) . '" class="img-responsive" alt="Thumbnail of ' . $result["title"] . '">'
        . '</a>'
        . '</td>';

      // Render the title with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'view-content.php?pointer=' . $result["pointer"] . '">' . $result["title"] . '</a>'
        . '</td>';

      // Render the description plainly.
      $html .= '<td>' . $result["descri"] . '</td>';

      $html .= '</tr>';
    }

    return $html;
  }

  /**
   * Queries the database for the search term and returns the interior of a
   * `<tbody>` with the proper results.
   *
   * @todo React to empty results.
   *
   * @return String
   */
  public function renderTablePlants() {
    global $application;

    $html  = "";
    $query = $this->connection->prepare("SELECT id, scientific_name, habitat FROM plants WHERE (" . $this->renderPlantQuery() . ") AND scientific_name != ''");

    $query->execute($this->populatePlantArray());

    foreach ($query->fetchAll() as $current=>$result) {
      $html .= '<tr>';

      // Render the thumbnail with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'view-plant.php?id=' . $result["id"] . '">'
        . '<img src="#" class="img-responsive" alt="Thumbnail of ' . $result["scientific_name"] . '">'
        . '</a>'
        . '</td>';

      // Render the scientific name with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'view-plant.php?id=' . $result["id"] . '">' . $result["scientific_name"] . '</a>'
        . '</td>';

      // Render the habitat plainly.
      $html .= '<td>' . $result["habitat"] . '</td>';

      $html .= '</tr>';
    }

    return $html;
  }

  /**
   * Queries the database for the search term and returns the interior of a
   * `<tbody>` with the proper results.
   *
   * @todo Remove from Production
   *
   * @return String
   */
  public function renderTableViewer() {
    global $application;

    $html  = "";
    $array = array();
    $query = $this->connection->prepare("SELECT pointer FROM manuscripts WHERE " . $this->renderContentQuery());

    $query->execute($this->populateContentArray());

    foreach ($query->fetchAll() as $current=>$result) {
      // Trim the pointer.
      $result["pointer"] = trim($result["pointer"]);

      // Continue if we have already linked this.
      if (in_array($result["pointer"], $array)) {
        continue;
      }

      $parent  = json_decode(file_get_contents($application->constructParameterURL("GetParent", $result["pointer"])), true);
      $object  = array();
      $pointer = "";

      if ($parent["parent"] === -1) {
        $object  = json_decode(file_get_contents($application->constructParameterURL("dmGetCompoundObjectInfo", $result["pointer"])), true);
        $pointer = $result["pointer"];
      } else {
        $object  = json_decode(file_get_contents($application->constructParameterURL("dmGetCompoundObjectInfo", $parent["parent"])), true);
        $pointer = $parent["parent"];
      }

      // Add the parent pointer to the array.
      array_push($array, $pointer);

      // If we're on an item that is not a compound object, skip.
      if ($parent["parent"] === -1 && in_array("message", array_keys($object))) {
        continue;
      }

      // Determine the title and description of the compound object.
      $quick = $this->connection->prepare("SELECT title, descri FROM manuscripts WHERE pointer = :pointer");
      $quick->execute(array(":pointer" => $pointer));

      $data = $quick->fetch();

      // Add all compound object page pointers.
      foreach ($object["page"] as $key=>$page) {
        array_push($array, trim($page["pageptr"]));
      }

      $html .= '<tr>';

      // Render the thumbnail.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'manuscript-viewer.php?pointer=' . $object["page"][0]["pageptr"] . '#page/1/mode/2up">'
        . '<img src="http://digital.tcl.sc.edu/utils/getthumbnail/collection/hsn/id/' . $pointer . '" class="img-responsive" alt="' . $data["title"] . '">'
        . '</a>'
        . '</td>';

      // Render the title.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . 'manuscript-viewer.php?pointer=' . $object["page"][0]["pageptr"] . '#page/1/mode/2up">' . $data["title"] . '</a>'
        . '</td>';

      // Render the description.
      $html .= '<td>' . $data["descri"] . '</td>';

      $html .= '</tr>';
    }

    return $html;
  }

  /**
   * Populates an array for PDO prepared statements for the manuscript or
   * mineral results.
   *
   * @return Array
   */
  private function populateContentArray() {
    $array = array();

    foreach ($this->contentKeys as $key) {
      $array[":" . $key] = "%" . $this->search . "%";
    }

    return $array;
  }

  /**
   * Populates an array for PDO prepared statements for the plant results.
   *
   * @return Array
   */
  private function populatePlantArray() {
    $array = array();

    foreach ($this->plantKeys as $key) {
      $array[":" . $key] = "%" . $this->search . "%";
    }

    return $array;
  }

  /**
   * Renders the query for manuscript or mineral SQL prepared statements.
   *
   * @return String
   */
  private function renderContentQuery() {
    $query = "";

    foreach ($this->contentKeys as $key) {
      $query .= $key . " ILIKE :" . $key . " OR ";
    }

    return substr($query, 0, -4);
  }

  /**
   * Renders the query for plant SQL prepared statements.
   *
   * @return String
   */
  private function renderPlantQuery() {
    $query = "";

    foreach ($this->plantKeys as $key) {
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

  /**
   * Mutators
   */
  public function setSearch($search) {
    $this->search = $search;
  }
}

$searcher = new Searcher();
