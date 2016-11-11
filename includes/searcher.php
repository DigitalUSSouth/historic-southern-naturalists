<?php
/**
 * The behind-the-scenes revolving around `/search`
 */
class Searcher {
  private $search;

  /**
   * Constructor
   */
  public function __construct() {
    global $application;

    $this->search     = "";
    $this->connection = $application->getConnection();
  }

  /**
   * Renders the `<form action="">` value.
   *
   * @return String
   */
  public function renderFormAction() {
    global $application;

    return php_sapi_name() === "cli-server"
      ? $application->getURL() . "search.php"
      : $application->getURL() . "search";
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
    $array = array();
    $query = "SELECT pointer, title, contri FROM manuscripts WHERE ";

    foreach (array("contri", "covera", "date", "descri", "publis", "relati", "subjec", "title") as $key) {
      $query .= $key . " ILIKE :" . $key . " OR ";

      $array[":" . $key] = '%' . $this->search . '%';
    }

    $prepare = $this->connection->prepare(substr($query, 0, -4));
    $prepare->execute($array);

    foreach ($prepare->fetchAll() as $current=>$result) {
      $html .= '<tr>';

      // Render the thumbnail with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . $this->renderPage('view-content') . '?pointer=' . $result["pointer"] . '">'
        . '<img src="http://digital.tcl.sc.edu/utils/getthumbnail/collection/hsn/id/' . $result["pointer"]. '" class="img-responsive" alt="Thumbnail of ' . $result["title"] . '">'
        . '</a>'
        . '</td>';

      // Render the title with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . $this->renderPage('view-content') . '?pointer=' . $result["pointer"] . '">' . $result["title"] . '</a>'
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
  public function renderTablePlants() {
    global $application;

    $html  = "";
    $array = array();
    $query = "SELECT id, scientific_name, habitat FROM plants WHERE (";

    foreach (array("family", "identified_by", "scientific_name") as $key) {
      $query .= $key . " ILIKE :" . $key . " OR ";

      $array[":" . $key] = '%' . $this->search . '%';
    }

    $prepare = $this->connection->prepare(substr($query, 0, -4) . ") AND scientific_name != ''");
    $prepare->execute($array);

    foreach ($prepare->fetchAll() as $current=>$result) {
      $html .= '<tr>';

      // Render the thumbnail with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . $this->renderPage('view-plant') . '?id=' . $result["id"] . '">'
        . '<img src="#" class="img-responsive" alt="Thumbnail of ' . $result["scientific_name"] . '">'
        . '</a>'
        . '</td>';

      // Render the scientific name with a link.
      $html .= '<td>'
        . '<a href="' . $application->getURL() . $this->renderPage('view-plant') . '?id=' . $result["id"] . '">' . $result["scientific_name"] . '</a>'
        . '</td>';

      // Render the habitat plainly.
      $html .= '<td>' . $result["habitat"] . '</td>';

      $html .= '</tr>';
    }

    return $html;
  }

  /**
   * Created only because I still can't figure out how to tell PHP to ignore
   * extensions via CLI server, comes this functionality to literally render
   * `.php` if I'm on a CLI server.
   *
   * @todo Look up how to fix this.
   *
   * @param  String $page -- The URL to be rendered.
   * @return String
   */
  private function renderPage($page) {
    return php_sapi_name() === "cli-server"
      ? $page . ".php/"
      : $page . "/";
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
