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
<!-- VideoJS playlist -->

<div class="player-container">
  <div class="row">
  <div class="col-md-9">
   <video
     id="video"
     class="video-js"
     height='500px'
     width='700px'
     controls
     autoplay="false">
     <source src="videos/vignette1.mp4" type="video/mp4">
   </video>
</div>
<div class="col-md-3">
   <div class="vjs-playlist">
     <!--
       The contents of this element will be filled based on the
       currently loaded playlist
     -->
   </div>
 </div>
 </div><!-- End row -->
<div/><!-- End player-container -->
 <script>
   var player = videojs('video');
   player.playlist([
   {
     name: 'Gathering Specimens',
     description: 'Description goes here',
     duration: 195, // Length of the video in seconds
     sources: [{ src: 'videos/vignette1.mp4', type: 'video/mp4' },],
     thumbnail: [
       {
         src: 'videos/poster1.png'
       }
     ]
   },
   {
     name: 'Preparing the Oven Press',
     description: 'Description goes here',
     duration: 197,
     sources: [
       { src: 'videos/vignette2.mp4', type: 'video/mp4' },
     ],
     thumbnail: [
       {
         src: 'videos/poster2.png'
       }
     ]
   },
   {
     name: 'Removing the Specimens From the Oven',
     description: 'Description goes here',
     duration: 193,
     sources: [
       { src: 'videos/vignette3.mp4', type: 'video/mp4' },
     ],
     thumbnail: [
       {
         src: 'videos/poster3.png'
       }
     ]
   },
   {
     name: 'Digitizing the Data',
     description: 'Description goes here',
     duration: 199,
     sources: [
       { src: 'videos/vignette4.mp4', type: 'video/mp4' },
     ],
     thumbnail: [
       {
         src: 'videos/poster4.png'
       }
     ]
   }]);
   // Initialize the playlist-ui plugin with no option (i.e. the defaults).
   player.playlistUi();
 </script>

<!-- END VideoJS playlist -->



<?php
require "includes/footer.php";
?>
