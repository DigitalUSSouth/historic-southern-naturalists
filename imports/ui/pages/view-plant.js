import './view-plant.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Plants } from '/imports/api/plants.js';

Template.viewPlant.onCreated(function () {
  Tracker.autorun(() => {
    if (FlowRouter.getParam('id')) {
      this.subscribe('symbiota-viewer', FlowRouter.getParam('id'));
    }
  });
});

Template.viewPlant.helpers({
  plant() {
    return Plants.findOne({ id: FlowRouter.getParam('id') });
  }
});
