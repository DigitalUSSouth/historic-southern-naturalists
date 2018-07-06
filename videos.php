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

<video id="mainVideo" width=520 height=440 autoplay controls></video>
<h3>Click on a video below to view</h3>
<br/>
<video class="thumbnail" width=200 height=200>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette1.mp4" type="video/mp4">
</video>
<video class="thumbnail" width=200 height=200>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette2.mp4" type="video/mp4">
</video>
<video class="thumbnail" width=200 height=200>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette3.mp4#t=1" type="video/mp4">
</video>
<video class="thumbnail" width=200 height=200>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette4.mp4#t=1" type="video/mp4">
</video>


<script>
var videos = document.querySelectorAll(".thumbnail");
for (var i = 0; i < videos.length; i++) {
    videos[i].addEventListener('click', clickHandler, false);
}

function clickHandler(el) {
    var mainVideo = document.getElementById("mainVideo");
    mainVideo.src = el.srcElement.currentSrc;
}
</script>

<hr />

<?php require_once "includes/footer.php"; ?>
