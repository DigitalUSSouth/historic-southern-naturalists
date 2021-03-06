<?php
/**
 * view-content.php
 *
 * The viewer page for manuscripts.
 */

require_once "includes/application.php";
require_once "includes/content.php";

$content = new Content($_GET["pointer"]);

$application->setTitle("Manuscript Viewer - " . $content->getData("title"));

$details = array(
  "date"   => "Date",
  "contri" => "Contributor",
  "publis" => "Publisher",
  "covera" => "Coverage",
  "relati" => "Relations"
);

$hasDetails = false;

// Loop through each detail key to see if any exist.
foreach ($details as $key=>$label) {
  if ($content->hasData($key)) {
    $hasDetails = true;

    break;
  }
}

require_once "includes/header.php";
?>
  <div class="row">
    <div class="col-xs-12">
      <h1><?php print $content->getData("title"); ?></h1>
    </div>
  </div>

  <div class="row">
    <?php if ($content->hasData("descri") || $content->hasData("subjec")): ?>
      <div class="col-sm-6">
        <?php if ($content->hasData("descri")): ?>
          <h2>Description</h2>

          <p class="text-justify"><?php print $content->renderBasicData("descri"); ?></p>
        <?php endif; ?>

        <?php if ($content->hasData("subjec")): ?>
          <hr>

          <h4>Subject</h4>

          <p class="text-justify"><?php print $content->renderBasicData("subjec"); ?></p>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="col-sm-6">
      <?php if ($hasDetails): ?>
        <h2>Details</h2>

        <dl>
          <?php foreach ($details as $key=>$label): ?>
            <?php print $content->renderDetailData($label, $key); ?>
          <?php endforeach; ?>
        </dl>
      <?php endif; ?>

      <?php if ($content->hasData("transc")): ?>
        <h2>Transcript</h2>

        <pre><?php print $content->getData("transc"); ?></pre>
      <?php endif; ?>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-xs-12">
      <img src="<?php print $content->getImage(); ?>" class="img-responsive" alt="<?php print $content->getData("title"); ?>">
    </div>
  </div>
<?php require_once "includes/footer.php"; ?>
