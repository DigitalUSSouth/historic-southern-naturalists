import './results.html';

import { ReactiveVar } from 'meteor/reactive-var';
import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

Template.results.onCreated(function () {
  Template.instance().results = new ReactiveVar(false);

  Tracker.autorun(() => {
    if (FlowRouter.getParam('query') !== undefined) {
      Meteor.call('search', FlowRouter.getParam('query'), (error, result) => {
        if (error) {
          console.log(error);
        } else {
          this.results.set(result);
        }
      });
    }
  });
});

Template.results.helpers({
  results() {
    return Template.instance().results.get();
  }
});
