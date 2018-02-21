<?php
/**
 * videos.php
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
    <h1 style="color: #00747a; font-weight: bold;">Video</h1>

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

<!-- VideoJS Player -->
<div class="player-container">
  <div class="row">
    <div class="col-md-9">
<!-- Video Player -->
      <video
        id="video"
        class="video-js"
        height='600px'
        width='850px'
        controls
        autoplay="false">
        <source src="videos/vignette1.mp4" type="video/mp4">
      </video>
    </div><!-- End col-md-9 -->
    <div class="col-md-3">
<!-- Playlist -->
      <div class="vjs-playlist">
         <!--
           The contents of this element will be filled based on the
           currently loaded playlist
         -->
      </div>
    </div><!-- End col-md-3 -->
 </div><!-- End row -->
<div/><!-- End player-container -->
<!-- END VideoJS Player -->

<!-- This is to have some space below the video player so that the video is in
the middle of the page -->
<div id="spacer"/>

<?php
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

<hr />

<?php require_once "includes/footer.php"; ?>
