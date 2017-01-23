<?php
/**
 * video.php
 *
 * The video gallery page of the website.
 */

require "includes/application.php";
$application->setTitle("Timeline");

require "includes/header.php";

?>
<div class="row">
  <div class="col-xs-12">

    <h1>Video</h1>

    <p class="lead">To play a video scroll down and click on the thumbnail of the video. Your video will start playing in the large video player at the bottom of this page.</p>
  </div>
</div>

<!-- Row for thumbnail -->
<div class="row">
  <div class="col-xs-4">
    <video class="thumbnail" width=200 height=200>
      <source src="/videos/vignette1.mp4">
      Your browser does not support HTML5 video
    </video>
  </div>
  <div class="col-xs-8">
    <h5>Big Buck Bunny</h5>
    <p>Description goes here</p>
  </div>
</div>
<hr />
<!-- End of row for thumbnail -->
<!-- Row for thumbnail -->
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
<!-- End of row for thumbnail -->
<!-- Row for thumbnail -->
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
<!-- End of row for thumbnail -->



<!-- Main Video -->
<!--
<div class="row">
  <div id="scroll-here"></div>
  <div class="col-xs-12" style="margin:0px;padding:0px;">
    <div class="jumbotron" style="margin:0px;padding:0px;border-style:solid;">
      <div class="embed-responsive embed-responsive-4by3">
        <video id="mainVideo" autoplay controls style="margin-top:0px;"></video>
        <h3 class="text-center" style="margin-top:30%">Click on a video below to view</h3>
    </div>
    </div>
  </div>
</div>
<br />
<div class="row">
  <div class="col-xs-12">
    <p><span style="font-size:35px;">&#8648;</span>&nbsp;&nbsp;&nbsp;Scroll up to view another video&nbsp;&nbsp;&nbsp;<span style="font-size:35px;">&#8648;</span></p>
  </div>
</div>
-->

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
      // While there are file to read which are not '.' '..' or '.DS_Store'
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
        } // End of if statement
      } // End of while loop
    } // End of if statement
  }// End of if statement
?>



<?php
require "includes/footer.php";
?>
