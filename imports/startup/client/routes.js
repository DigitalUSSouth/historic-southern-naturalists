import { BlazeLayout } from 'meteor/kadira:blaze-layout';
import { FlowRouter } from 'meteor/kadira:flow-router';

// Import layout.
import '/imports/ui/layouts/scaffolding.js';

// Import pages.
import '/imports/ui/pages/home.js';
import '/imports/ui/pages/results.js';
import '/imports/ui/pages/search.js';
import '/imports/ui/pages/timeline.js';
import '/imports/ui/pages/video.js';
import '/imports/ui/pages/view-rocks.js';
import '/imports/ui/pages/viewer.js';

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

FlowRouter.route('/search/:type/:query', {
  action() {
    BlazeLayout.render('scaffolding', { main: 'results' });
  }
});

FlowRouter.route('/viewer/:type/:id', {
  action(params) {
    if (params.type === 'rock') {
      BlazeLayout.render('scaffolding', { main: 'viewRocks' });
    } else if (params.type === 'plant') {
      BlazeLayout.render('scaffolding', { main: 'viewer' });
    } else {
      FlowRouter.go('/search');
    }
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
