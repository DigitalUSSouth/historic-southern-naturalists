import './view-rocks.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Rocks } from '/imports/api/rocks.js';

Template.viewRocks.onCreated(function () {
  Tracker.autorun(function () {
    Meteor.subscribe('rock-viewer', FlowRouter.getParam('id'));
  });
});

Template.viewRocks.helpers({
  rock() {
    return Rocks.findOne({
      _id: FlowRouter.getParam('id')
    });
  }
});
