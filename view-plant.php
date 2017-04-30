<?php
/**
 * view-plant.php
 *
 * The viewer page for plants.
 */

require_once "includes/application.php";
require_once "includes/planter.php";

$planter = new Planter($_GET["id"]);

$application->setTitle("Plant Viewer - " . $planter->getData("scientific_name"));

$details = array(
  "event_date"    => "Date",
  "family"        => "Family",
  "genus"         => "Genus",
  "habitat"       => "Habitat",
  "identified_by" => "Identified By"
);

$location = array(
  "locality"          => "Locality",
  "county"            => "County",
  "state_province"    => "State Province",
  "country"           => "Country",
  "decimal_latitude"  => "Latitude",
  "decimal_longitude" => "decimal_longitude"
);

$hasDetails  = false;
$hasLocation = false;

foreach ($details as $key=>$label) {
  if ($planter->hasData($key)) {
    $hasDetails = true;

    break;
  }
}

foreach ($location as $key=>$label) {
  if ($planter->hasData($key)) {
    $hasLocation = true;

    break;
  }
}

require_once "includes/header.php";
?>
  <div class="row">
    <div class="col-xs-12">
      <h1><?php print $planter->getData("scientific_name"); ?></h1>
    </div>
  </div>

  <div class="row">
    <?php if ($hasDetails): ?>
      <div class="col-sm-6">
        <h2>Details</h2>

        <dl>
          <?php foreach ($details as $key=>$label): ?>
            <?php print $planter->renderData($label, $key); ?>
          <?php endforeach; ?>
        </dl>
      </div>
    <?php endif; ?>

    <?php if ($hasLocation): ?>
      <div class="col-sm-6">
        <h2>Location</h2>

        <dl>
          <?php foreach ($location as $key=>$label): ?>
            <?php print $planter->renderData($label, $key); ?>
          <?php endforeach; ?>
        </dl>
      </div>
    <?php endif; ?>
  </div>
<?php require_once "includes/footer.php"; ?>
