/**
 * hsn.js v1.0.0
 * Copyright (c) 2016. Center for Digital Humanities.
 * Licensed under MIT.
 */

if (window.location.pathname.indexOf('search') > -1 && $('div[role="tabpanel"] > table').length) {
  $('div[role="tabpanel"] > table').dataTable();
}

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

    var scrollHere = "<div id=\"scroll-here\">"
    var videoPlayer = "<div id=\"jumbotron\"><div class=\"row\"></div><div class=\"col-xs-12\" style=\"margin:0px;padding:0px;\"><div  class=\"jumbotron\" style=\"margin:0px;padding:0px;border-style:solid;\"><div class=\"embed-responsive embed-responsive-4by3\"><video id=\"mainVideo\" autoplay controls style=\"margin-top:0px;\"></video><h3 class=\"text-center\" style=\"margin-top:30%\">Click on a video below to view</h3></div></div></div></div><br /><div class=\"row\"><div class=\"col-xs-12\"> </div>";



    console.log(el.srcElement.parentElement.parentElement);

    var jumbotron = document.getElementById("jumbotron");
    if(jumbotron === null){
      // The big video player has not be created so create one
      console.log("Creating jumbotron");
      console.log(el.srcElement);
      // Create the scroll point to scroll the view to the main video but put it above the thumbnail to not confuse the user
      el.srcElement.parentElement.parentElement.insertAdjacentHTML('beforebegin',scrollHere)

      // Create the main video player below the thumbnail
      el.srcElement.parentElement.parentElement.insertAdjacentHTML('afterend',videoPlayer);

      // Get the mainVideo element to display the video in
      var mainVideo = document.getElementById("mainVideo");
      mainVideo.src = el.srcElement.currentSrc;
      // Scroll up to view the mainVideo
      var scrollHere = document.getElementById("scroll-here")
      scrollHere.scrollIntoView({ behavior: 'smooth'});
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
