<?php
/**
 * browse-all.php
 *
 * Browse page before the Manuscript Viewer page.
 */

require_once "includes/application.php";

$application->setTitle("Manuscript Viewer Browse");

$manuscript = "SELECT *
               FROM manuscripts";

$prepare = $application->getConnection()->prepare($manuscript);

$prepare->execute();

require_once "includes/header.php";
?>
  <div class="row page-header">
    <div class="col-xs-12">
      <h1 style="color: #00747a; font-weight: bold;">Manuscript Viewer Browse</h1>

      <p class="lead">Type a keyword into the Search bar. Results are returned immediately.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <table class="table table-hover table-responsive" data-plugin="dataTable">
        <thead>
          <tr>
            <th>Thumbnail</th>
            <th>Title</th>
            <th>Pages</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($prepare->fetchAll() as $key=>$object): ?>
            <?php $pointer = trim($object["pointer"]); ?>
            <tr>
              <td>
                <a href="<?php print $application->getURL(); ?>manuscript-viewer.php?pointer=<?php print $pointer; ?>&collection=<?php print $object["collection"]; ?>#page/1/mode/2up">
                  <img src="<?php print $application->buildManuscriptThumbURL($pointer, $object["collection"]); ?>" class="img-responsive" alt="<?php print $object["title"]; ?>">

                  <span class="hide"><?php print $pointer; ?></span>
                </a>
              </td>

              <td>
                <a href="<?php print $application->getURL(); ?>manuscript-viewer.php?pointer=<?php print $pointer; ?>&collection=<?php print $object["collection"]; ?>#page/1/mode/2up">
                  <?php print $object["title"]; ?>
                </a>
              </td>

              <td><?php print $object["count"]; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  while ($row = mysqli_fetch_array($manuscript) ){
    echo "<tr>";
    echo "<td><a href= 'manuscript-viewer.php'><img src=".$row['thumb']"</a>  "
  }
<?php require_once "includes/footer.php"; ?>
