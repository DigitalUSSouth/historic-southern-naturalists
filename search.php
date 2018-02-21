<?php
/**
 * search.php
 *
 * The search page and its possible results.
 */

require_once "includes/application.php";

if (isset($_GET["search"])) {
  $application->setTitle("Search - " . $_GET["search"]);
} else {
  $application->setTitle("Search");
}

require_once "includes/searcher.php";

if (isset($_GET["search"])) {
  if (trim($_GET["search"]) === "") {
    header("Location: /search.php");

    return;
  }

  $searcher = new Searcher($_GET["search"]);
}

$types = array(
  "manuscripts" => array(
    "Title",
    "Description"
  ),

  "plants" => array(
    "Scientific Name",
    "Habitat"
  ),

  "minerals" => array(
    "Title",
    "Description"
  ),

  "inserts" => array(
    "Title",
    "Description"
  ),

  "fossils" => array(
    "Title",
    "Description"
  )
);

require_once "includes/header.php";
?>
  <div class="row page-header">
    <div class="col-xs-12">
      <h1 style="color: #00747a; font-weight: bold;">Search</h1>

      <p class="lead">Search by date, naturalist, geographic location, material type, etc. Just type in your term and click the SEARCH button. Then click the tabs to see results for each discipline.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <form class="form-inline" autocomplete="off" action="search.php">
        <legend class="hide">Search</legend>

        <fieldset>
          <div class="form-group">
            <label for="search" class="control-label col-xs-2">Search</label>

            <div class="col-xs-10">
              <div class="input-group">
                <?php if (isset($_GET["search"])): ?>
                  <input type="text" class="form-control" id="search" name="search" value="<?php print $searcher->getSearch(); ?>">
                <?php else: ?>
                  <input type="text" class="form-control" id="search" name="search">
                <?php endif; ?>

                <span class="input-group-btn">
                  <button type="submit" class="btn btn-default">Search</button>
                </span>
              </div>
            </div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>

  <?php if (isset($_GET["search"])): ?>
    <div>
      <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($types as $media=>$array): ?>
          <li role="presentation" class="<?php print array_keys($types)[0] === $media ? "active" : ""; ?>">
            <a href="#<?php print $media; ?>" aria-controls="<?php print $media; ?>" role="tab" data-toggle="tab"><?php print ucfirst($media); ?></a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div class="tab-content">
        <?php foreach ($types as $media=>$headers): ?>
          <div role="tabpanel" class="tab-pane <?php print array_keys($types)[0] === $media ? "active" : ""; ?>" id="<?php print $media; ?>">
            <table class="table table-hover table-responsive" data-plugin="dataTable">
              <thead>
                <tr>
                  <th>Thumbnail</th>

                  <?php foreach ($headers as $header): ?>
                    <th><?php print $header; ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>

              <tbody>
                <?php print $searcher->renderTable($media); ?>
              </tbody>
            </table>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
<?php require_once "includes/footer.php"; ?>
