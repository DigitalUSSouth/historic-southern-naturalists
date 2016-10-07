import './results.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Content } from '/imports/api/content.js';

import '/imports/ui/components/no-search-results.js';
import '/imports/ui/components/results-image.js';
import '/imports/ui/components/results-link.js';

Template.results.onCreated(function () {
  Tracker.autorun(function () {
    if (FlowRouter.getParam('query')) {
      Meteor.subscribe('contentdm-search', FlowRouter.getParam('query'));
    }
  });
});

Template.results.helpers({
  settings() {
    return {
      fields: [
        {
          key:   'pointer',
          tmpl:  Template.resultsImage,
          label: 'Thumbnail'
        }, {
          key:   'title',
          tmpl:  Template.resultsLink,
          label: 'Title'
        }, {
          key:   'contri',
          label: 'Contributor'
        }
      ],

      class:          'table table-bordered table-hover table-responsive',
      collection:     Content.find(),
      noDataTmpl:     Template.noSearchResults,
      rowsPerPage:    5,
      showRowCount:   true,
      showNavigation: 'auto',
      useFontAwesome: true
    }
  }
});
