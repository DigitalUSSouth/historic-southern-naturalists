import './viewer.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';

import { Plants } from '/imports/api/plants.js';

Template.viewer.helpers({
  plant() {
    return Plants.findOne({
      id: parseInt(FlowRouter.current().params.id, 10)
    });;
  }
});
