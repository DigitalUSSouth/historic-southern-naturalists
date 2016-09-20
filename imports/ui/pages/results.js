import './results.html';

import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Plants } from '/imports/api/plants.js';
import { Rocks } from '/imports/api/rocks.js';

import '/imports/ui/components/no-search-results.js';
import '/imports/ui/components/results-link.js';

Template.results.onCreated(function () {
  Tracker.autorun(function () {
    if (FlowRouter.getParam('type') === 'plants') {
      Meteor.subscribe('plants', FlowRouter.getParam('query'));
    } else if (FlowRouter.getParam('type') === 'rocks') {
      Meteor.subscribe('rock-search', FlowRouter.getParam('query'));
    }
  });
});

Template.results.helpers({
  results() {
    if (FlowRouter.getParam('type') === 'plants') {
      return Plants.find();
    } else if (FlowRouter.getParam('type') === 'rocks') {
      return Rocks.find();
    }
  },

  // https://github.com/aslagle/reactive-table#settings
  settings() {
    let fields = [];

    if (FlowRouter.getParam('type') === 'plants') {
      fields = [
        {
          key:   'scientificName',
          tmpl:  Template.resultsLink,
          label: 'Scientific Name'
        }, {
          key:   'family',
          label: 'Family'
        }, {
          key:   'identifiedBy',
          label: 'Identified By'
        }
      ];
    } else if (FlowRouter.getParam('type') === 'rocks') {
      fields = [
        {
          key:   'title',
          tmpl:  Template.resultsLinkRock,
          label: 'Title'
        }, {
          key:   'description',
          label: 'Description'
        }
      ];
    }

    return {
      class:          'table table-bordered table-hover table-responsive',
      fields:         fields,
      noDataTmpl:     Template.noSearchResults,
      rowsPerPage:    5,
      showRowCount:   true,
      showNavigation: 'auto',
      useFontAwesome: true

    };
  }
});
