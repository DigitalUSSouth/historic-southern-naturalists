<?php
/**
 * timeline.php
 *
 * The timeline page. The data used here is within the shared Google Drive
 * folder. Contact an administrator if you do not have access to it.
 */

require_once "includes/application.php";

$application->setTitle("Timeline");

require_once "includes/header.php";
?>
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Timeline</h1>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <iframe src="https://cdn.knightlab.com/libs/timeline3/latest/embed/index.html?source=1suOy5pdYL1R6eD9ssildwnSdxXoFSFda8wy2uRkIlQc&font=Default&lang=en&initial_zoom=2&height=650" width="100%" height="650" frameborder="0"></iframe>
    </div>
  </div>
<?php require_once "includes/footer.php"; ?>
