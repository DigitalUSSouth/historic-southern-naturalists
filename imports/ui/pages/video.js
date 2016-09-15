import './video.html';

import { Template } from 'meteor/templating';

Template.video.events({
  // WebKit Affects:   Chrome, Safari
  // Moz Affects:      Firefox (Supposedly: Not Successful on OS X)
  // MS Affects:       Internet Explorer, Microsoft Edge (Theoretical - Never Tested)
  // Default Affects:  Opera (Theoretical - Never Tested)
  'webkitfullscreenchange video, mozfullscreenchange video, MSFullscreenChange video, fullscreenchange video'() {
    console.log('document.fullScreen:        ', document.fullScreen);
    console.log('document.mozFullScreen:     ', document.mozFullScreen);
    console.log('document.webkitIsFullScreen:', document.webkitIsFullScreen);
  }
});
