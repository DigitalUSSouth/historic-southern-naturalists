import { FlowRouter } from 'meteor/kadira:flow-router';
import { BlazeLayout } from 'meteor/kadira:blaze-layout';

import '/imports/components/video.js';

FlowRouter.route('/', {
  name: 'home',
  action() {
    BlazeLayout.render('video');
  }
});
