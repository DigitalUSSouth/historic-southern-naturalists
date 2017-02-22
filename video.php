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

<div class="row page-header">
  <div class="col-xs-12">
    <h1>Video</h1>
    <p class="lead">To view a video click on the thumbnails on the right.</p>
  </div>
</div>


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
require "includes/footer.php";
?>
