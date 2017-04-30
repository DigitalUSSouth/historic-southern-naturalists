<?php
/**
 * about.php
 *
 * Self-explanatory website about page.
 */

require "includes/application.php";

$application->setTitle("About");

require "includes/header.php";
?>

<div class="row page-header">
  <div class="col-xs-12">
    <h1>About</h1>

    <p class="lead">This website is the result of an Advanced Support for Innovative Research Excellence grant from the University of South Carolina.</p>
  </div>
</div>

<div class="row">
  <div class="col-xs-12">
    <p>The project objectives were threefold:</p>

    <ol>
      <li>digitize objects and associated archives of significant historic collections from the University of South Carolina’s collecting institutions  (A.C. Moore Herbarium, McKissick Museum, and South Caroliniana Library)</li>
      <li>merge those digital records of natural history collections into a comprehensive, cross-referenced database accessible to the public online</li>
      <li>utilize newly created digital images to enhance exhibits through interactive touchscreens</li>
    </ol>

    <p>These archival collections not only document the 19th-century investigations of the natural environment in South Carolina, but they also illustrate the establishment and advancement of the field of natural history.  The material collections, including botanical, fossil, and mineral specimens, exemplify the natural world that existed two hundred years ago, and are sometimes the only representatives of these taxa in existence due to extinction and loss of geologic localities.  Additionally, these objects are not always appropriate for exhibition due to their sensitive or fragile nature; digitizing them will allow for use in on-site exhibitions as well as an online resource.</p>

    <p>High priority collections to be digitized include the equipment, minerals, field labels, and manuscripts of Thomas Cooper (1759-1839); the minerals, field labels, books and documents of Lewis Reeves Gibbes (1810-1894); and the botanical specimens, equipment, manuscripts, and monographs of Andrew Charles Moore (1866-1928).  As funding and time allow, additional material from other Southern naturalists will be added to the database, the website, and physical exhibits at McKissick Museum.</p>

    <p>Other features of this website include video vignettes, and a timeline of natural history investigation.  This resources is in no way complete, however, it focuses on the naturalists associated with the University and includes significant events in US and global history.  Rather than highlighting historic naturalists, the video vignettes feature modern researchers from the University as they demonstrate the collection, documentation, and preservation of objects for future generations.</p>
  </div>
</div>

<?php require "includes/footer.php"; ?>
