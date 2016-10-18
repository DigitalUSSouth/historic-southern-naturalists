import './results.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { Tracker } from 'meteor/tracker';
import { Meteor } from 'meteor/meteor';

import { Content } from '/imports/api/content.js';
import { Plants } from '/imports/api/plants.js';

import '/imports/library/tab.js';

import '/imports/ui/components/content-results-image.js';
import '/imports/ui/components/content-results-link.js';
import '/imports/ui/components/no-search-results.js';
import '/imports/ui/components/plant-results-image.js';
import '/imports/ui/components/plant-results-link.js';

Template.results.onCreated(function () {
  Tracker.autorun(() => {
    if (FlowRouter.getParam('query')) {
      this.subscribe('symbiota-search', FlowRouter.getParam('query'));
      this.subscribe('contentdm-search', FlowRouter.getParam('query'));
    }
  });
});

Template.results.helpers({
  manuscripts() {
    return {
      fields: [
        {
          key:   'pointer',
          tmpl:  Template.contentResultsImage,
          label: 'Thumbnail'
        }, {
          key:   'title',
          tmpl:  Template.contentResultsLink,
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
  },

  plants() {
    return {
      fields: [
        {
          key:   'catalogNumber',
          tmpl:  Template.plantResultsImage,
          label: 'Thumbnail'
        }, {
          key:   'scientificName',
          tmpl:  Template.plantResultsLink,
          label: 'Scientific Name'
        }, {
          key:   'habitat',
          label: 'Habitat'
        }
      ],

      class:          'table table-bordered table-hover table-responsive',
      collection:     Plants.find(),
      noDataTmpl:     Template.noSearchResults,
      rowsPerPage:    5,
      showRowCount:   true,
      showNavigation: 'auto',
      useFontAwesome: true
    }
  }
});
