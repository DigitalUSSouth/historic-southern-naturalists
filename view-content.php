<?php
require "includes/application.php";
require "includes/content.php";

$content = new Content($_GET["pointer"]);

$application->setTitle("Manuscript Viewer - " . $content->getData("title"));

require "includes/header.php";
?>
<div class="row">
  <div class="col-xs-12">
    <h1><?php print $content->getData("title"); ?></h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <h2>Description</h2>

    <p class="text-justify"><?php print $content->getData("descri"); ?></p>

    <?php if ($content->getData("subjec") !== ""): ?>
      <hr>

      <p class="text-justify"><?php print $content->getData("subjec"); ?></p>
    <?php endif; ?>
  </div>

  <div class="col-sm-6">
    <h2>Details</h2>

    <dl>
      <?php print $content->renderData("Date", "date"); ?>

      <?php print $content->renderData("Contributor", "contri"); ?>

      <?php print $content->renderData("Publisher", "publis"); ?>

      <?php print $content->renderData("Coverage", "covera"); ?>

      <?php print $content->renderData("Relations", "relati"); ?>
    </dl>
  </div>
</div>

<hr>

<div class="row">
  <div class="col-xs-12">
    <img src="<?php print $content->getImage(); ?>" class="img-responsive" alt="<?php print $content->getData("title"); ?>">
  </div>
</div>
