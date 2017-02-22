<?php
/**
 * header.php
 *
 * To be rendered at the beginning of every page.
 */
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
<body>
  <?php if (!$application->isManuscriptViewer()): ?>
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a href="<?php print $application->getURL(); ?>" class="navbar-brand">Historic Southern Naturalists</a>
        </div>

        <div class="collapse navbar-collapse" id="navbar">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php print $application->getURL(); ?>">Home</a></li>
            <li><a href="<?php print $application->getURL(); ?>browse-viewer.php">Flip Viewer</a></li>
            <li><a href="<?php print $application->getURL(); ?>search.php">Search</a></li>
            <li><a href="<?php print $application->getURL(); ?>gallery.php">Gallery</a></li>
            <li><a href="<?php print $application->getURL(); ?>timeline.php">Timeline</a></li>
            <li><a href="<?php print $application->getURL(); ?>video.php">Video</a></li>
          </ul>
        </div>
      </div>
    </nav>
  <?php endif; ?>

  <div class="container">
