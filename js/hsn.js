/**
 * hsn.js v1.0.0
 * Copyright (c) 2016. Center for Digital Humanities.
 * Licensed under MIT.
 */

// Assign all DataTables.
$('[data-plugin="dataTable"]').dataTable();

// Code for the video player on the Video page
$(document).ready(function () {
  // Get all the thumbnail elements
  var videos = document.querySelectorAll(".thumbnail");
  for(var i = 0; i < videos.length; i++){
    // Add click listeners to play the video when clicked on
    videos[i].addEventListener('click', clickHandler, false);
  }
  // Click listener
  function clickHandler(el){

    // HTML for the vido player element to be added in
    var videoPlayer = "<div id=\"scroll-here\"><div id=\"jumbotron\"><div class=\"row\"></div><div class=\"col-xs-12\" style=\"margin:0px;padding:0px;\"><div  class=\"jumbotron\" style=\"margin:0px;padding:0px;border-style:solid;\"><div class=\"embed-responsive embed-responsive-4by3\"><video id=\"mainVideo\" autoplay controls style=\"margin-top:0px;\"></video><h3 class=\"text-center\" style=\"margin-top:30%\">Click on a video below to view</h3></div></div></div></div><br /><div class=\"row\"><div class=\"col-xs-12\"> </div>";
    console.log(el.srcElement.parentElement.parentElement);
    var jumbotron = document.getElementById("jumbotron");
    // The big video player has not be created so create one
    if(jumbotron === null){
      console.log("Creating jumbotron");
      console.log(el.srcElement);
      // Create the main video player below the thumbnail
      el.srcElement.parentElement.parentElement.insertAdjacentHTML('afterend',videoPlayer);
      // Get the mainVideo element to display the video in
      var mainVideo = document.getElementById("mainVideo");
      mainVideo.src = el.srcElement.currentSrc;
      // Scroll up to view the mainVideo
    //  var scrollHere = document.getElementById("scroll-here")

      $('html, body').animate({
        scrollTop: $("#scroll-here").offset().top
      },1000);

      //scrollHere.scrollIntoView({ behavior: 'smooth'});
    }else{
      // The big video player has been created so destroy it
      console.log("Destroying jumbotron");
      jumbotron.outerHTML = "";
      delete jumbotron;

      // Remove the scroll point
      var scroll = document.getElementById("scroll-here");
      scroll.outerHTML = "";
      delete scroll;
    }

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
