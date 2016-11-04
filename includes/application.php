<?php
/**
 * The global assistant of this website.
 */
class Application {
  private $url;
  private $title;
  private $connection;

  /**
   * Constructor
   */
  public function __construct() {
    $this->url        = "http://" . $_SERVER["HTTP_HOST"] . "/";
    $this->title      = "";
    $this->connection = pg_connect("host=localhost port=5432 dbname=hsn");
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
   * Renders all `<meta>` content.
   *
   * Full (unofficial) list:
   *   https://github.com/joshbuchea/HEAD#meta
   *
   * @return String
   */
  public function renderMeta() {
    ob_start();
    ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    return ob_get_clean();
  }

  /**
   * Renders all minified `.css` files.
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
    $files = array("bootstrap-3.3.7.min.css", "font-awesome-4.6.3.min.css");

    foreach ($files as $file) {
      $html .= '<link rel="stylesheet" href="' . $this->url . 'css/' . $file . '">';
    }

    return $html;
  }

  /**
   * Renders all navigational links.
   *
   * @return String
   */
  public function renderNavigation() {
    $html  = "";
    $links = array("Home", "Search", "Timeline");

    foreach ($links as $link) {
      $html .= '<li><a href="' . $this->url;

      // Because we don't have a home.php, this shit needs to be here.
      if ($link !== "Home") {
        $html .= strtolower($link);

        // This _should_ only execute when a local web-server is created.
        if (php_sapi_name() === "cli-server") {
          $html .= ".php";
        }
      }

      $html .= '">' . $link . '</a></li>';
    }

    return $html;
  }

  /**
   * Renders all minified `.js` files.
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
    $files = array("jquery-3.1.1.min.js", "bootstrap-3.3.7.min.js");

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

  /**
   * Mutators
   */
  public function setTitle($title) {
    $this->title = $title;
  }
}

$application = new Application();
global $application;
