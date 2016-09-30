import './results.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Content } from '/imports/api/content.js';

Template.results.onCreated(function () {
  Tracker.autorun(function () {
    if (FlowRouter.getParam('query')) {
      Meteor.subscribe('contentdm-search', FlowRouter.getParam('query'));
    }
  });
});

Template.results.helpers({
  results() {
    return Content.find();
  }
});
