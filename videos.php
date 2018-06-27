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
    <h1>Video</h1>

    <p class="lead">No, we don’t have videos of historic southern naturalists at work.  However, you can see how ordinary objects become museum artifacts in these video vignettes (starring modern southern naturalists).  Click on a video thumbnail to get started.</p>
  </div>
</div>

<div class="row">
  <div class="col-xs-4">
    <video width=500 height=400 controls>
      <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette1.mp4">
	  Your browser does not support the video tag.
    </video>
  </div>

  <div class="col-xs-8">
    <h5>Video Vignette 1</h5>
  </div>
  
    <div class="col-xs-4">
    <video width=500 height=400 controls>
      <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette2.mp4">
	  Your browser does not support the video tag.
    </video>
  </div>

  <div class="col-xs-8">
    <h5>Preparing the Oven Press</h5>
  </div>
  
    <div class="col-xs-4">
    <video width=500 height=400 controls>
      <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette3.mp4">
	  Your browser does not support the video tag.
    </video>
  </div>

  <div class="col-xs-8">
    <h5>Removing Specimens From the Oven</h5>
  </div>
  
    <div class="col-xs-4">
    <video width=500 height=400 controls>
      <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette4.mp4">
	  Your browser does not support the video tag.
    </video>
  </div>

  <div class="col-xs-8">
    <h5>Digitizing Data</h5>
  </div>

<hr />

<?php require_once "includes/footer.php"; ?>
