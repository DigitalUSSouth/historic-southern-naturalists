<?php
/**
 * manuscript-viewer.php
 *
 * The manuscript-viewer page.
 *
 * NOTE: The element `#BookReader` MUST have the id set to that, otherwise
 *       the third-party plugin will need to be changed to match accordingly.
 */
require_once "includes/application.php";
require_once "includes/content.php";

$application->setIsManuscriptViewer(true);
$application->setTitle("Manuscript Viewer");

require_once "includes/header.php";
?>
  <div id="BookReader" data-pointer="<?php print $_GET["pointer"]; ?>" data-collection="<?php print $_GET["collection"]; ?>"></div>
<?php require_once "includes/footer.php"; ?>
