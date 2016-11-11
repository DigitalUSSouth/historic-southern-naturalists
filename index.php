<?php
require "includes/application.php";
require "includes/header.php";
?>
  <div class="row">
    <div class="col-xs-12">
      <p class="lead">Please note, this site is still under alpha development.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-4">
      <h3>42" Touch Monitors</h3>

      <ul class="list-unstyled">
        <li>
          <a href="<?php print $application->getURL(); ?>timeline.php">Timeline</a>
        </li>

        <li>
          <a href="<?php print $application->getURL(); ?>search.php">Mineral Search</a>
        </li>

        <li>Manuscript Viewer <em class="text-muted">Not Started</em></li>
      </ul>
    </div>

    <div class="col-xs-4">
      <h3>Tablets</h3>

      <ul class="list-unstyled">
        <li>Gallery Layout Map <em class="text-muted">Not Started</em></li>
      </ul>
    </div>

    <div class="col-xs-4">
      <h3>Desktop</h3>

      <ul class="list-unstyled">
        <li>Video Gallery <em class="text-muted">Need: Videos</em></li>
      </ul>
    </div>
  </div>
<?php require "includes/footer.php"; ?>
