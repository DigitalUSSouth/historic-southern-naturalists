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

<head>
	<style>
		.row{text-align: center;}
		
		.thumbnail{
			display: inline-block;
			text-align: left;
		}
	</style>
</head


<div id="scroll-here"></div>

<div class="row page-header">
  <div class="col-xs-12">
    <h1>Video</h1>

    <p class="lead">We have videos of historic southern naturalists at work. Click on a video thumbnail to get started.</p>
  </div>
</div>

<div class="row">
<div class="thumbnail>
<video id="mainVideo" width=920 height=840 autoplay controls></video>
<h3>Click on a video below to view</h3>
<br/>
<video class="thumbnail" width=300 height=300>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette1.mp4" type="video/mp4">
</video>
<video class="thumbnail" width=300 height=300>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette2.mp4" type="video/mp4">
</video>
<video class="thumbnail" width=300 height=300>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette3.mp4#t=1" type="video/mp4">
</video>
<video class="thumbnail" width=300 height=300>
    <source src="https://s3.amazonaws.com/dussstoragebucket/HSNVideos/vignette4.mp4#t=1" type="video/mp4">
</video>
</div>
</div>
                                                                                                         


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
