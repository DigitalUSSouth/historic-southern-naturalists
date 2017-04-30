<?php
/**
 * browse-all.php
 *
 * Browse page before the Manuscript Viewer page.
 */

require "includes/application.php";

$application->setTitle("Manuscript Viewer Browse");

$prepare = $application->getConnection()->prepare("
  SELECT page.pointer, parent.title, counter.count, page.collection
  FROM   manuscripts AS page, manuscripts AS parent
  INNER JOIN (
    SELECT   DISTINCT(parent_object) AS parent_pointer, COUNT(compound_page) AS count
    FROM     manuscripts
    WHERE    compound_page != '-1'
      AND    ((collection = 'hsn' AND media LIKE 'Letter%') OR collection = 'kmc')
    GROUP BY parent_object
  ) counter ON parent.pointer = counter.parent_pointer
  WHERE page.compound_page = '0'
    AND parent.is_compound_object = true
    AND page.parent_object = parent.pointer
    AND ((page.collection = 'hsn' AND page.media LIKE 'Letter%') OR page.collection = 'kmc')
");

$prepare->execute();

require "includes/header.php";
?>
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Manuscript Viewer Browse</h1>

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
<?php require "includes/footer.php"; ?>
