<?php
/**
 * index.php
 *
 * Home-base of the entire website.
 */

require "includes/application.php";
require "includes/header.php";

// TODO: Find better images.
// TODO: Need a portrait of Gibbes.
$info = array(
  array(
    "image"   => "a-c-moore.jpg",
    "caption" => "Portrait of A. C. Moore"
  ),
  array(
    "image"   => "thomas-cooper.jpg",
    "caption" => "Portrait of Thomas Cooper"
  )
);
?>
  <div class="row">
    <div class="col-xs-12">
      <?php // TODO: Expecting an image montage of some sort to insert here. ?>
      <?php // TODO: This header needs to either leave or be stylized better, it's repeating the logo 50 pixels below the logo. ?>
      <h1 class="text-center index-title">Historic Southern Naturalists</h1>

      <p>During the colonial period in North America, centers of scientific thought were often associated with port cities such as Boston, New York and Philadelphia.  However, the southernmost port city of Charleston should not be overlooked, as it was the center of intense scientific investigations through the mid-1800s.  The founding of South Carolina College in 1801 drew the attention of scientific pursuits to the inland city of Columbia, and there began an important legacy of scientific collections.  Since that time, the University of South Carolina (formerly South Carolina College) has amassed a collection of both archives and objects dating to the earliest years of the field of natural history.  This website cross-references digitized images of object and archival collections documenting the work of significant naturalists associated with the University, who worked in the South.</p>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-2">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[0]["image"]; ?>" class="img-responsive" alt="<?php print $info[0]["caption"]; ?>">

      <div class="caption"><?php print $info[0]["caption"]; ?></div>
    </div>

    <div class="col-sm-10">
      <p>Moore entered South Carolina College in 1883 and was the institution's first ever honors graduate, in 1887.  After working in education for twelve years, he began graduate studies at the University of Chicago, and obtained his doctoral degree in 1900.  In the same year, he began his career at South Carolina College as an Associate Professor, within the department of Biology, Geology, and Mineralogy.  In 1904, he was promoted to full professor, and the following year appointed as the first Chairman of the Department of Biology.  Dr. Moore remained in the Department of Biology until his death in 1928, except for the terms of 1908-9 and 1913-4, when he served as Acting President of the University.</p>

      <p>As a biologist, Moore was particularly interested in botany.  Among his research interests was the formulation of concepts on reduction division, as observed in liverworts.  Considerable evidence in the McKissick Museum collection exists for Moore's usage of the word "meiosis" in its modern context, as the first ever in biology.  Dr. Moore also founded the current herbarium with his own collections in 1907.</p>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-9">
      <p>Cooper arrived at SC College in 1819 as Professor of Chemistry, Minerals and Geology, and became the second president of the college after Jonathan Maxcy’s death in 1820.  The following year the SC legislature purchased Cooper’s mineral and fossil collections for the college, thereby establishing the first collection owned by the institution.  Although originally used to supplement classroom lectures, the geology collection’s intrinsic research value, and its potential as a core museum collection, was quickly recognized.  Cooper’s instructional replacement, Richard Brumby, began a detailed catalogue in 1848, which resulted in the Catalogue of the Collection of Minerals in the College of S. C.  During the Civil War, the geology collection was discarded and subsequently rescued by Professors LeConte and Woodrow.  Cataloging work was not resumed until 1902, when A.C. Moore appointed Daniel Strobel Martin to the task, resulting in many additions to both the collection and the Catalogue.</p>
    </div>

    <div class="col-sm-3">
      <img src="<?php print $application->getURL(); ?>/img/<?php print $info[1]["image"]; ?>" class="img-responsive" alt="<?php print $info[1]["caption"]; ?>">

      <div class="caption"><?php print $info[1]["caption"]; ?></div>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-sm-3">
      <img src="http://placehold.it/200x250" class="img-responsive" alt="Need portrait of Gibbes">

      <div class="caption">Need portrait of Gibbes</div>
    </div>

    <div class="col-sm-9">
      <p>A student of Cooper’s and 1829 graduate of South Carolina College, Gibbes became a professor at the College of Charleston, and was well connected to many prominent scientists in the United States and abroad through his membership in the AAAS.  During the mid-19th-century, he was a leading expert on American crabs, and authored the first publications on South Carolina’s marine algae.  An avid collector of the natural world, he described his own cabinet of crustaceans in 1850 as “the largest I believe at the South,” and Gibbes was considered by many to have been South Carolina’s most versatile scientist.  While his interest in mineralogy is overshadowed by his other accomplishments, Gibbes’ large collection of minerals was significant enough that USC purchased it shortly after his death.</p>
    </div>
  </div>
<?php require "includes/footer.php"; ?>
