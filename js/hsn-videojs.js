/*
* hsn-videojs.js is for controlling the video player on the video tab. This is using
* the VideJS library and the VideoJS-Playlist plugin. The JSON list player.playlist
* defines the videos to be played. The source for this open-source project is located
* here <https://github.com/videojs/video.js>. The source for the playlist plugin is
* located here <https://github.com/jgallen23/videojs-playLists>.
*/

var player = videojs('video');
player.playlist([
// Video 1
{
  name: 'Gathering Specimens', // Main title for the playlist
  description: 'Description goes here', // Small description on the playlist
  duration: 195, // Length of the video in seconds
  sources: [ // Sources for the video (can have multiple if in different formats)
    { src: 'videos/vignette1.mp4', type: 'video/mp4' },
  ],
  thumbnail: [ // The poster thumbnail for the playlist
    { src: 'videos/poster1.png'}
  ]
},
// Video 2
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
// Video 3
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
// Video 4
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

// Mute the player on default
//(HACK: Remove this in production but the audio is annoying in debugging)
player.volume(0);
