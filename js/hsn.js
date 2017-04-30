/**
 * hsn.js v1.0.0
 * Copyright (c) 2017. Center for Digital Humanities.
 * Licensed under MIT.
 */

// Assign all DataTables.
$('[data-plugin="dataTable"]').dataTable();

$(document).ready(function () {
  // Code for the video player on the Video page
  // Get all the thumbnail elements
  var videos = document.querySelectorAll(".thumbnail");
  for(var i = 0; i < videos.length; i++){
    // Add click listeners to play the video when clicked on
    videos[i].addEventListener('click', clickHandler, false);
  }
  // Click listener
  function clickHandler(el){
    // Get the mainVideo element to display the video in
    var mainVideo = document.getElementById("mainVideo");
    // Get the source from the clicked on video to play in the main video
    mainVideo.src = el.srcElement.currentSrc;
    // Scroll up to view the mainVideo
    var scrollHere = document.getElementById("scroll-here")
    scrollHere.scrollIntoView({ behavior: 'smooth'});
  }

});

// TODO - Production: Remove this when the respective image is gone.
$('#hideImage').click(function (event) {
  if ($('#galleryImage').is(':hidden')) {
    $(this).text('Hide Image');
    $('#galleryImage').show();
  } else {
    $(this).text('Show Image');
    $('#galleryImage').hide();
  }
});
