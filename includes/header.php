<?php
/**
 * header.php
 *
 * To be rendered at the beginning of every page.
 */

$navigation = array(
  'Home',
  'Browse All',
  'Search',
  'Gallery',
  'Timeline',
  'Video'
);
?><!DOCTYPE html>
<html>
<head>
  <?php print $application->renderMeta(); ?>

  <title><?php print $application->renderTitle(); ?></title>

  <?php print $application->renderCSS(); ?>
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
            <?php foreach ($navigation as $item): ?>
              <?php $link = $item === 'Home' ? '' : str_replace(' ', '-', strtolower($item)) . ".php"; ?>
              <li>
                <a href="<?php print $application->getURL() . $link; ?>"><?php print $item; ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </nav>
  <?php endif; ?>

  <div class="container">
