import './timeline.html';

import { Template } from 'meteor/templating';

Template.timeline.onRendered(function timelineRendered() {
  // JSON Docs: https://timeline.knightlab.com/docs/json-format.html

  // Configuration variable for the timeline settings
  var timelineConfig={
    type:     'timeline',
    width:    '100%',
    height:   '600',
    source:   '/timeline.json',
    embed_id: 'timeline'
  };

  // If the VMM is undefined then create the timeline using createStoryJS
  if (typeof VMM === 'undefined'){
    createStoryJS(timelineConfig);
  } else {
    // If the VMM already exists (this occurs when the page is rendered, then you switch to another page and then back to the timeline)
    // Use the VMM.timline to re-render the timeline
    // Without re-rendering the jQuery does not recreate the DOM for the timeline and the timeline will not appear
    new VMM.Timeline(timelineConfig.embed_id, timelineConfig.width, timelineConfig.height,timelineConfig.source).init(timelineConfig);
  }
});
