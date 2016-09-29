import './rock-viewer.html';

import { ReactiveVar } from 'meteor/reactive-var';
import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

Template.rockViewer.onCreated(function () {
  Template.instance().rock  = new ReactiveVar(false);
  Template.instance().image = new ReactiveVar(false);

  Tracker.autorun(() => {
    if (FlowRouter.getParam('pointer') !== undefined) {
      Meteor.call('info', FlowRouter.getParam('pointer'), (error, result) => {
        if (error) {
          console.log(error);
        } else {
          this.rock.set(result);
        }
      });

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

Template.rockViewer.helpers({
  rock() {
    return Template.instance().rock.get();
  },

  image() {
    return Template.instance().image.get();
  },

  hasDetails() {
    const rock = Template.instance().rock.get();

    const isDetailed = (detail) => {
      return typeof detail === 'string' && detail.trim() !== '';
    };

    return isDetailed(rock.date) || isDetailed(rock.contri) || isDetailed(rock.publis) || isDetailed(rock.covera) || isDetailed(rock.relati);
  },

  isObject(info) {
    return typeof info === 'object';
  }
});
