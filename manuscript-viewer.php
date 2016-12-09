<?php
require "includes/application.php";
require "includes/content.php";

$application->setIsManuscriptViewer(true);
$application->setTitle("Manuscript Viewer");

require "includes/header.php";
?>
  <?php // Note: This element MUST have the `id` attribute as `BookReader` ?>
  <div id="BookReader" data-pointer="<?php print $_GET["pointer"]; ?>" data-collection="<?php print $_GET["collection"]; ?>"></div>
<?php require "includes/footer.php"; ?>
