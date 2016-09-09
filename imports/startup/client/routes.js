import { FlowRouter } from 'meteor/kadira:flow-router';
import { BlazeLayout } from 'meteor/kadira:blaze-layout';

// Import layout.
import '/imports/ui/layouts/scaffolding.js';

// Import component.
import '/imports/ui/components/navigation.js';

// Import pages.
import '/imports/ui/pages/home.js';
import '/imports/ui/pages/search.js';
import '/imports/ui/pages/timeline.js';
import '/imports/ui/pages/video.js';

FlowRouter.route('/', {
  action() {
    BlazeLayout.render('scaffolding', { main: 'home' });
  }
});

FlowRouter.route('/search', {
  action() {
    BlazeLayout.render('scaffolding', { main: 'search' });
  }
});

FlowRouter.route('/timeline', {
  action() {
    BlazeLayout.render('scaffolding', { main: 'timeline' });
  }
});

FlowRouter.route('/video', {
  action() {
    BlazeLayout.render('scaffolding', { main: 'video' });
  }
});
