import './results.html';

import { Template } from 'meteor/templating';

import { Plants } from '/imports/api/plants.js';

import '/imports/ui/components/no-search-results.js';
import '/imports/ui/components/results-link.js';

Template.results.helpers({
  results() {
    return Plants.find();
  },

  // https://github.com/aslagle/reactive-table#settings
  settings() {
    return {
      class:          'table table-bordered table-hover table-responsive',
      noDataTmpl:     Template.noSearchResults,
      rowsPerPage:    5,
      showRowCount:   true,
      showNavigation: 'auto',
      useFontAwesome: true,
      fields: [
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
      ]
    };
  }
});
