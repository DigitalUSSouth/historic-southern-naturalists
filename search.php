<?php
require "includes/application.php";

if (isset($_GET["search"])) {
  $application->setTitle("Search - " . $_GET["search"]);
} else {
  $application->setTitle("Search");
}

require "includes/header.php";
require "includes/searcher.php";

if (isset($_GET["search"])) {
  if (trim($_GET["search"]) === "") {
    header("Location: /search");

    return;
  }

  $searcher->setSearch($_GET["search"]);
}
?>
  <div class="row">
    <div class="col-xs-12">
      <h1>Search</h1>

      <p class="lead">Search within a localized plant or rock collection.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <form class="form-inline" autocomplete="off" action="<?php print $searcher->renderFormAction(); ?>">
        <legend class="hide">Search</legend>

        <fieldset>
          <div class="form-group">
            <label for="search" class="control-label col-xs-2">Search</label>

            <div class="col-xs-10">
              <div class="input-group">
                <input type="text" class="form-control" id="search" name="search" value="<?php print $searcher->getSearch(); ?>">

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
        <li role="presentation" class="active">
          <a href="#manuscripts" aria-controls="manuscripts" role="tab" data-toggle="tab">Manuscripts</a>
        </li>

        <li role="presentation">
          <a href="#plants" aria-controls="plants" role="tab" data-toggle="tab">Plants</a>
        </li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="manuscripts">
          <table class="table table-hover table-responsive">
            <thead>
              <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Contributor</th>
              </tr>
            </thead>

            <tbody>
              <?php print $searcher->renderTableManuscripts(); ?>
            </tbody>
          </table>
        </div>

        <div role="tabpanel" class="tab-pane" id="plants">
          <table class="table table-hover table-responsive">
            <thead>
              <tr>
                <th>Thumbnail</th>
                <th>Scientific Name</th>
                <th>Habitat</th>
              </tr>
            </thead>

            <tbody>
              <?php print $searcher->renderTablePlants(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php endif; ?>
<?php require "includes/footer.php"; ?>
