<?php
/**
 * header.php
 *
 * To be rendered at the beginning of every page.
 */

$navigation = array(
  'Home',
  'About the Naturalists',
  'About the Project',
  'Browse All',
  'Search',
  'Gallery',
  'Timeline',
  'Videos'
);
?><!DOCTYPE html>
<html>
<head>
  <?php print $application->renderMeta(); ?>

  <title><?php print $application->renderTitle(); ?></title>

  <?php print $application->renderCSS(); ?>

  <link href="http://vjs.zencdn.net/5.11/video-js.min.css" rel="stylesheet">
  <script src="http://vjs.zencdn.net/5.11/video.min.js"></script>
  <?php print $application->renderScripts(); ?>


  <!--<script src="js/videojs-playlist-ui.js"></script>
  <script src="js/videojs-playlist.js"></script>
  <link href="css/videojs-playlist-ui.vertical.css" rel="stylesheet">-->

</head>
<style>
  body {
    background-color: #695e4a;
    color: black; 
    font-size: 15px;
    margin-top: 50px; 
  }
</style>
<body>
  <?php if (!$application->isManuscriptViewer()): ?>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

<img src="img/Filmstrip_2.png" alt="filmstrip" style="width: 100%;">
        </div>

        <br><br>
        <div class="collapse navbar-collapse" id="navbar" style="align: center">
          <ul class="nav navbar-nav navbar-right nav-pills" style="text-align: center;">
            <li class="nav-item">
               <a class="nav-link active" href="<?php print $application->getURL(); ?>" style="color: #3cb6ce">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>#about-naturalist" style="color: #3cb6ce">About the Naturalists</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>#about-project" style="color: #3cb6ce">About the Project</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>browse-all.php" style="color: #3cb6ce">Browse</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>search.php" style="color: #3cb6ce">Search</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>gallery.php" style="color: #3cb6ce">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>timeline.php" style="color: #3cb6ce">Timeline</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php $application->getURL();?>videos.php" style="color: #3cb6ce">Videos</a>
            </li>


            <?php /*foreach ($navigation as $item): ?>
              <?php $link = $item === 'Home' ? '' : str_replace(' ', '-', strtolower($item)) . ".php"; ?>
              <li>
                <a href="<?php print $application->getURL() . $link; ?>"><?php print $item; ?></a>
              </li>
            <?php endforeach; */ ?>
          </ul>
        </div>
      </div>
    </nav>
  <?php endif;?>
  <div class="container">
