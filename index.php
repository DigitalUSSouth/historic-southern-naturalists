<?php
/**
 * index.php
 *
 * Home-base of the entire website. 
 */

require_once "includes/application.php";
require_once "includes/header.php";

// TODO: Find better images.
$info = array(
  array(
    "image"   => "thomas-cooper.jpg",
    "caption" => "Portrait of Thomas Cooper"
  ),

  array(
    "image"   => "lewis-gibbes.jpg",
    "caption" => "Portrait of Lewis Gibbes"
  ),

  array(
    "image"   => "a-c-moore.jpg",
    "caption" => "Portrait of A. C. Moore"
  )
);
?>
  
  <div class="row">
    <div class="col-xs-12">
      <?php // TODO: Expecting an image montage of some sort to insert here. ?>
      <?php // TODO: This header needs to either leave or be stylized better, it's repeating the logo 50 pixels below the logo. ?>
      <h1 class="text-center index-title" style="color: #00747a; font-weight: bold;" id="about-naturalists">About the Naturalists</h1>

      <p>During the colonial period in North America, centers of scientific thought were often associated with port cities such as Boston, New York and Philadelphia.  However, the southernmost port city of Charleston should not be overlooked, as it was the center of intense scientific investigations through the mid-1800s.  The founding of South Carolina College in 1801 drew the attention of scientific pursuits to the inland city of Columbia, and there began an important legacy of scientific collections.  Since that time, the University of South Carolina (formerly South Carolina College) has amassed a collection of both archives and objects dating to the earliest years of the field of natural history.  This website cross-references digitized images of object and archival collections documenting the work of significant naturalists associated with the University, who worked in the South.</p>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-3">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[0]["image"]; ?>" class="img-responsive" alt="<?php print $info[0]["caption"]; ?>">

      <div class="caption"><?php print $info[0]["caption"]; ?></div>
    </div>

    <div class="col-sm-9">
      <p>Cooper arrived at SC College in 1819 as Professor of Chemistry, Minerals and Geology, and became the second president of the college after Jonathan Maxcy’s death in 1820.  The following year the SC legislature purchased Cooper’s mineral and fossil collections for the college, thereby establishing the first collection owned by the institution.  Although originally used to supplement classroom lectures, the geology collection’s intrinsic research value, and its potential as a core museum collection, was quickly recognized.  Cooper’s instructional replacement, Richard Brumby, began a detailed catalogue in 1848, which resulted in the Catalogue of the Collection of Minerals in the College of S. C.  During the Civil War, the geology collection was discarded and subsequently rescued by Professors LeConte and Woodrow.  Cataloging work was not resumed until 1902, when A.C. Moore appointed Daniel Strobel Martin to the task, resulting in many additions to both the collection and the Catalogue.</p>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-9">
      <p>A student of Cooper’s and 1829 graduate of South Carolina College, Gibbes became a professor at the College of Charleston, and was well connected to many prominent scientists in the United States and abroad through his membership in the AAAS.  During the mid-19th-century, he was a leading expert on American crabs, and authored the first publications on South Carolina’s marine algae.  An avid collector of the natural world, he described his own cabinet of crustaceans in 1850 as “the largest I believe at the South,” and Gibbes was considered by many to have been South Carolina’s most versatile scientist.  While his interest in mineralogy is overshadowed by his other accomplishments, Gibbes’ large collection of minerals was significant enough that USC purchased it shortly after his death.</p>
    </div>

    <div class="col-sm-3">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[1]["image"]; ?>" class="img-responsive" alt="<?php print $info[1]["caption"]; ?>">

      <div class="caption"><?php print $info[1]["caption"]; ?></div>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-2">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[2]["image"]; ?>" class="img-responsive" alt="<?php print $info[2]["caption"]; ?>">

      <div class="caption"><?php print $info[2]["caption"]; ?></div>
    </div>

    <div class="col-sm-10">
      <p>Moore entered South Carolina College in 1883 and was the institution's first ever honors graduate, in 1887.  After working in education for twelve years, he began graduate studies at the University of Chicago, and obtained his doctoral degree in 1900.  In the same year, he began his career at South Carolina College as an Associate Professor, within the department of Biology, Geology, and Mineralogy.  In 1904, he was promoted to full professor, and the following year appointed as the first Chairman of the Department of Biology.  Dr. Moore remained in the Department of Biology until his death in 1928, except for the terms of 1908-9 and 1913-4, when he served as Acting President of the University.</p>

      <p>As a biologist, Moore was particularly interested in botany.  Among his research interests was the formulation of concepts on reduction division, as observed in liverworts.  Considerable evidence in the McKissick Museum collection exists for Moore's usage of the word "meiosis" in its modern context, as the first ever in biology.  Dr. Moore also founded the current herbarium with his own collections in 1907.</p>
    </div>
  </div>

  <div class="row page-header">
    <div class="col-xs-12">
      <h1 class="text-center index-title" style="color: #00747a; font-weight: bold;" id="about-project">About the Project</h1>

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

  <hr>

  <div class="row">
    <div class="col-xs-12">
      <h3>Outline: Videos</h3>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-xs-12">
      <h3>Outline: Timeline</h3>
    </div>
  </div>

<?php require_once "includes/footer.php"; ?>
