<?php
class Application {
  private $url;
  private $title;

  public function __construct() {
    $this->url   = "http://" . $_SERVER["HTTP_HOST"] . "/";
    $this->title = "";
  }

  public function renderTitle() {
    return $this->title === ""
      ? "Historic Southern Naturalists"
      : $this->title . " Historic Southern Naturalists";
  }

  public function renderMeta() {
    ob_start();
    ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    return ob_get_clean();
  }

  public function renderCSS() {
    $html  = "";
    $files = array("bootstrap-3.3.7.min.css", "font-awesome-4.6.3.min.css");

    foreach ($files as $file) {
      $html .= '<link rel="stylesheet" href="' . $this->url . 'css/' . $file . '">';
    }

    return $html;
  }

  public function renderScripts() {
    $html  = "";
    $files = array("jquery-3.1.1.min.js", "bootstrap-3.3.7.min.js");

    foreach ($files as $file) {
      $html .= '<script src="' . $this->url . 'js/' . $file . '"></script>';
    }

    return $html;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getURL() {
    return $this->url;
  }
}

$application = new Application();
