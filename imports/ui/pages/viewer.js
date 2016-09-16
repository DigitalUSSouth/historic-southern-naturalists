import './viewer.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Plants } from '/imports/api/plants.js';

Template.viewer.onCreated(function () {
  Tracker.autorun(function () {
    Meteor.subscribe('plant-viewer', FlowRouter.getParam('id'));
  });
});

Template.viewer.helpers({
  plant() {
    return Plants.findOne({
      id: parseInt(FlowRouter.getParam('id'), 10)
    });;
  }
});
