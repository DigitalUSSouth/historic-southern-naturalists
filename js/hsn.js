/**
 * hsn.js v1.0.0
 * Copyright (c) 2016. Center for Digital Humanities.
 * Licensed under MIT.
 */

$(document).ready(function () {
  if (window.location.pathname.indexOf('search') > -1 && $('div[role="tabpanel"] > table').length) {
    $('div[role="tabpanel"] > table').dataTable();
  }
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
