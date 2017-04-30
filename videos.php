<?php
/**
 * video.php
 *
 * The video gallery page of the website.
 */

require_once "includes/application.php";

$application->setTitle("Timeline");

require_once "includes/header.php";
?>
<div id="scroll-here"></div>

<div class="row page-header">
  <div class="col-xs-12">
    <h1>Video</h1>

    <p class="lead">No, we don’t have videos of historic southern naturalists at work.  However, you can see how ordinary objects become museum artifacts in these video vignettes (starring modern southern naturalists).  Click on a video thumbnail to get started.</p>
  </div>
</div>

<!-- Main Video -->
<div class="row">
  <div class="col-xs-12" style="margin:0px;padding:0px;">
    <!---->
    <div class="jumbotron" style="margin:0px;padding:0px;border-style:solid;">
      <div class="embed-responsive embed-responsive-4by3">
        <video id="mainVideo" autoplay controls style="margin-top:0px;"></video>
        <h3 class="text-center" style="margin-top:30%">Click on a video below to view</h3>
      </div>
    </div>
  </div>
</div>

<br />

<!-- Video Listing -->
<?php
  $dir = "video/";
  $videoWidth = 320;
  $videoHeight = 240;

  $description = "Video description goes here";
  // Check if the video directory exists
  if(is_dir($dir))
  {
    // If the directory exists then attempt to open it
    if($dh = opendir($dir))
    {
      // While there are file to read which are not . .. or .DS_Store
      while (($file = readdir($dh)) !== false)
      {
        if($file != '.' && $file != '..' && $file != '.DS_Store')
        {
          $fileName = substr($file,0,-4);
          // Display the video
          echo "
          <div class=\"row\">
            <div class=\"col-xs-4\">
              <video class=\"thumbnail\" width=200 height=200>
                <source src=\"". $dir . $file ."\" type=\"video/mp4\">
                Your browser does not support HTML5 video
              </video>
            </div>
            <div class=\"col-xs-8\">
              <h5>$fileName</h5>
              <p>$description</p>
            </div>
          </div>
          <hr />
          ";
        }
      }
    }
  }
?>
<div class="row">
  <div class="col-xs-4">
    <video class="thumbnail" width=200 height=200>
      <source src="http://video.webmfiles.org/big-buck-bunny_trailer.webm">
      Your browser does not support HTML5 video
    </video>
  </div>

  <div class="col-xs-8">
    <h5>Big Buck Bunny</h5>

    <p>Description goes here</p>
  </div>
</div>

<hr />

<?php require_once "includes/footer.php"; ?>
