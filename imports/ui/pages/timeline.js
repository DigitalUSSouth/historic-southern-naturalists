import './timeline.html';

import { Template } from 'meteor/templating';

Template.timeline.onRendered(function timelineRendered() {
  // JSON Docs: https://timeline.knightlab.com/docs/json-format.html
  createStoryJS({
    type:     'timeline',
    width:    '100%',
    height:   '600',
    source:   '/timeline.json',
    embed_id: 'timeline'
  });
});
