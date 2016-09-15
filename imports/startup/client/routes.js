import { FlowRouter } from 'meteor/kadira:flow-router';
import { BlazeLayout } from 'meteor/kadira:blaze-layout';
import { Meteor } from 'meteor/meteor';

// Import layout.
import '/imports/ui/layouts/scaffolding.js';

// Import component.
import '/imports/ui/components/navigation.js';

// Import pages.
import '/imports/ui/pages/home.js';
import '/imports/ui/pages/results.js';
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

FlowRouter.route('/search/:query', {
  subscriptions(params) {
    this.register('subscribePlants', Meteor.subscribe('plants', params.query));
  },

  action() {
    BlazeLayout.render('scaffolding', { main: 'results' });
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
