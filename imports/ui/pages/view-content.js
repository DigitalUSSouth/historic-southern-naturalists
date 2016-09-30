import './view-content.html';

import { ReactiveVar } from 'meteor/reactive-var';
import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Content } from '/imports/api/content.js';

Template.viewContent.onCreated(function () {
  Template.instance().image   = new ReactiveVar(false);

  Tracker.autorun(() => {
    if (FlowRouter.getParam('pointer')) {
      // Subscribe to the page-level item.
      Meteor.subscribe('contentdm-viewer', FlowRouter.getParam('pointer'));

      // Call the server and ask for the image information.
      Meteor.call('image', FlowRouter.getParam('pointer'), (error, result) => {
        if (error) {
          console.log(error);
        } else {
          this.image.set(result);
        }
      });
    }
  });
});

Template.viewContent.helpers({
  content() {
    return Content.find().fetch()[0];
  },

  image() {
    return Template.instance().image.get();
  }
});

