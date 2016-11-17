<?php
require "includes/application.php";
require "includes/content.php";

$application->setIsManuscriptViewer(true);
$application->setTitle("Manuscript Viewer");

require "includes/header.php";

// This element MUST have the `id` attribute as `BookReader`
?>
  <div id="BookReader" data-pointer="<?php print $_GET["pointer"]; ?>"></div>
<?php require "includes/footer.php"; ?>
