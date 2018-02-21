<?php
/**
 * gallery.php
 *
 * View the layout of the museum and interact with it.
 */

require_once "includes/application.php";

$application->setTitle("Gallery");

// None of these should have a link associated with it.
$objects = array(
  // Stairs.
  array(
    "rect" => array("x" => 420, "width" => 145, "height" => 170),
    "text" => array("x" => 460, "y" => 87, "text" => "Stairs")
  ),

  // Mechanical Room.
  array(
    "rect" => array("x" => 565, "width" => 190, "height" => 170),
    "text" => array("x" => 590, "y" => 87, "text" => "Mech Room")
  ),

  array("rect" => array("x" => 197, "y" => 000, "width" => 015, "height" => 170, "class" => "excused")), // top-left
  array("rect" => array("x" => 197, "y" => 245, "width" => 015, "height" => 140, "class" => "excused")), // bottom-left
  array("rect" => array("x" => 370, "y" => 157, "width" => 061, "height" => 015, "class" => "excused")), // middle-top
  array("rect" => array("x" => 345, "y" => 245, "width" => 100, "height" => 025, "class" => "excused")), // middle-bottom
  array("rect" => array("x" => 570, "y" => 245, "width" => 015, "height" => 140, "class" => "excused")), // bottom-right
);

// All of these should have a link associated with them.
// TODO: Wait for CONTENTdm to have the associated manuscript viewer metadata field.
//       Afterwards, attach it's metadata to the search results return in .scripts/contentdm.php
// TODO: Need to set up some sort of search-results-mirror page for specific pointers.
$exhibits = array(

);

require_once "includes/header.php";
?>
  <div class="row page-header">
    <div class="col-xs-12">
      <h1 style="color: #00747a; font-weight: bold;">Gallery</h1>

      <p><button type="button" class="btn btn-sm" id="hideImage">Hide Image</button></p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <?php // TOOD - Production: Remove this image. ?>
      <!-- <img src="<?php print $application->getURL(); ?>/img/temp-gallery-floor.png" class="img-responsive center-block" id="galleryImage"> -->

      <?php /*// TODO - Production: Add class `center-block`, remove `style` attribute. ?>
      <svg width="952" height="380" class="gallery-floor" style="position: absolute; top: 0; left: 109px;">
        <?php foreach ($objects as $key=>$object): ?>
          <g>
            <?php print $application->galleryObjectHelper("rect", $object["rect"]); ?>

            <?php if (array_key_exists("text", $object)): ?>
              <?php print $application->galleryObjectHelper("text", $object["text"]); ?>
            <?php endif; ?>
          </g>
        <?php endforeach; ?>

        <?php /* foreach ($exhibits as $key=>$exhibit): ?>
          <a xlink:href="<?php print $application->getURL(); ?>">

          </a>
        <?php endforeach; */ ?>
      </svg>
    </div>
  </div>
<?php require_once "includes/footer.php"; ?>
