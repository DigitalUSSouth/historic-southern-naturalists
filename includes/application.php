<?php
/**
 * The global assistant of this website.
 */
class Application {
  private $url;
  private $title;
  private $connection;
  private $isManuscriptViewer;

  /**
   * Constructor
   */
  public function __construct() {
    $contents = json_decode(file_get_contents(dirname(__FILE__) . "/../.scripts/pg-connect.json"), true)["php"];

    $this->url        = "http://" . $_SERVER["HTTP_HOST"] . "/";
    $this->title      = "";
    $this->connection = new PDO("pgsql:" . $contents["connection"], $contents["username"], $contents["password"]);

    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->isManuscriptViewer = false;
  }

  /**
   * Manuscript Thumbnail URL Constructor
   *
   * Constructs a URL based on the given pointer for a manuscript thumbnail
   * that is located within CONTENTdm.
   *
   * The default collection is 'hsn' due to the collection parameter only
   * necessary on the browse-viewer page.
   *
   * @param  String $pointer    -- Manuscript pointer.
   * @param  String $collection -- Collection the manuscript is in.
   * @return String
   */
  public function buildManuscriptThumbURL($pointer, $collection = "hsn") {
    return "http://digital.tcl.sc.edu/utils/getthumbnail/collection/" . $collection . "/id/" . $pointer;
  }

  /**
   * URL Constructor
   *
   * Constructs a CONTENTdm URL based on the given parameter and pointer.
   *
   * @param  String $parameter -- The API call.
   * @param  String $pointer   -- The pointer being utilized.
   * @return String
   */
  public function constructParameterURL($parameter, $pointer) {
    return "http://digital.tcl.sc.edu:81/dmwebservices/?q=" . $parameter . "/hsn/" . trim($pointer) . "/json";
  }

  /**
   * Gallery Helper
   *
   * Renders an element that belongs within an SVG and its associated
   * attributes.
   *
   * @param  String $element -- Type of HTML element.
   * @param  Array  $object  -- Element's attributes.
   * @return String
   */
  public function galleryObjectHelper($element, $object) {
    $html = "";

    // Run through all attributes.
    foreach ($object as $name=>$value) {
      if ($name === "text") {
        continue;
      }

      $html .= " " . $name . '="' . $value . '"';
    }

    // Adjust if it's a <text> element.
    if (array_key_exists("text", $object)) {
      return "<" . $element . $html . ">" . $object["text"] . "</" . $element . ">";
    } else {
      return "<" . $element . $html . "></" . $element . ">";
    }
  }

  /**
   * Renders the content of `<title>`.
   *
   * @return String
   */
  public function renderTitle() {
    return $this->title === ""
      ? "Historic Southern Naturalists"
      : $this->title . " - Historic Southern Naturalists";
  }

  /**
   * Renders all `<meta>` HTML.
   *
   * Full (unofficial) list:
   *   https://github.com/joshbuchea/HEAD#meta
   *
   * @return String
   */
  public function renderMeta() {
    $metadata = array(
      array("charset" => "utf-8"),
      array("http-equiv" => "x-ua-compatible", "content" => "ie=edge"),
      array("name" => "viewport", "content" => "width=device-width, initial-scale=1")
    );

    $html = "";

    foreach ($metadata as $key=>$element) {
      $html .= "<meta";

      foreach ($element as $name=>$value) {
        $html .= " " . $name . '="' . $value . '"';
      }

      $html .= "></meta>";
    }

    return $html;
  }

  /**
   * Renders a link to all CSS files.
   *
   * Note: These files may not appear after `git clone`. In order
   *       to have these files appear, execute `grunt` in a CLI.
   *
   *       View the Development section within README.md for more details.
   *
   * @return String
   */
  public function renderCSS() {
    $html  = "";
    $files = array("bootstrap-3.3.7.min.css", "font-awesome-4.6.3.min.css", "hsn-1.0.0.min.css");

    // Append files that need to be rendered for manuscript-viewer.php.
    if ($this->isManuscriptViewer) {
      array_push($files, "book-reader.css");
    }

    foreach ($files as $file) {
      $html .= '<link rel="stylesheet" href="' . $this->url . 'css/' . $file . '">';
    }

    return $html;
  }

  /**
   * Renders a link to all JavaScript files.
   *
   * Note: Some of these files may not appear after `git clone`. In
   *       order to have these files appear, execute `grunt` in a CLI.
   *
   *       View the Development section within README.md for more details.
   *
   * @return String
   */
  public function renderScripts() {
    $html  = "";
    $files = array(
      "jquery-3.1.1.min.js",
      "bootstrap-3.3.7.min.js",
      "dataTables-1.10.12.min.js",
      "dataTables-bootstrap-1.10.12.min.js",
      "hsn.js"
    );

    // Append files that need to be rendered for manuscript-viewer.php.
    if ($this->isManuscriptViewer) {
      $books = array(
        "jquery-ui-1.12.0.min.js",
        "jquery.browser.min.js",
        "dragscrollable.js",
        "jquery.colorbox-min.js",
        "jquery.ui.ipad.js",
        "jquery.bt.min.js",
        "book-reader.js",
      );

      foreach ($books as $file) {
        if (pathinfo($file)["extension"] !== "js") {
          continue;
        }

        array_push($files, 'bookreader/' . $file);
      }

      array_push($files, 'hsn-book-reader.js');
    }

    foreach ($files as $file) {
      $html .= '<script src="' . $this->url . 'js/' . $file . '"></script>';
    }

    return $html;
  }

  /**
   * Accessors
   */
  public function getConnection() {
    return $this->connection;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getURL() {
    return $this->url;
  }

  public function isManuscriptViewer() {
    return $this->isManuscriptViewer;
  }

  /**
   * Mutators
   */
  public function setIsManuscriptViewer($isManuscriptViewer) {
    $this->isManuscriptViewer = $isManuscriptViewer;
  }

  public function setTitle($title) {
    $this->title = $title;
  }
}

$application = new Application();
global $application;
