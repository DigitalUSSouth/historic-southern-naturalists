<?php
require "includes/application.php";

$application->setTitle("Timeline");

require "includes/header.php";
?>
<div id="scroll-here"></div>
<div class="row">
  <div class="col-xs-12">

    <h1>Video</h1>

    <p class="lead">To play a video scroll down and click on the thumbnail of the video. Your video will start playing in the large video player at the top.</p>
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

<script type="text/javascript">
  var videos = document.querySelectorAll(".thumbnail");
  for(var i = 0; i < videos.length; i++){
    videos[i].addEventListener('click', clickHandler, false);
  }
  function clickHandler(el){
    var mainVideo = document.getElementById("mainVideo");
    mainVideo.src = el.srcElement.currentSrc;
    var scrollHere = document.getElementById("scroll-here")
    scrollHere.scrollIntoView({ behavior: 'smooth'});
  }
</script>
