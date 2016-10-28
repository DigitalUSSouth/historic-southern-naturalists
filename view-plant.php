<?php
require "includes/application.php";
require "includes/planter.php";

$planter = new Planter($_GET["id"]);

$application->setTitle("Plant Viewer - " . $planter->getData("scientific_name"));

require "includes/header.php";
?>
<div class="row">
  <div class="col-xs-12">
    <h1><?php print $planter->getData("scientific_name"); ?></h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-6">
    <h2>Details</h2>

    <dl>
      <?php print $planter->renderData("Date", "event_date"); ?>

      <?php print $planter->renderData("Family", "family"); ?>

      <?php print $planter->renderData("Genus", "genus"); ?>

      <?php print $planter->renderData("Habitat", "habitat"); ?>

      <?php print $planter->renderData("Identified By", "identified_by"); ?>
    </dl>
  </div>

  <div class="col-sm-6">
    <h2>Location</h2>

    <dl>
      <?php print $planter->renderData("Locality", "locality"); ?>

      <?php print $planter->renderData("County", "county"); ?>

      <?php print $planter->renderData("State", "state_province"); ?>

      <?php print $planter->renderData("Country", "country"); ?>

      <?php print $planter->renderData("Latitude", "decimal_latitude"); ?>

      <?php print $planter->renderData("Longitude", "decimal_longitude"); ?>
    </dl>
  </div>
</div>
