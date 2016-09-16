import './results.html';

import { Template } from 'meteor/templating';

import { Plants } from '/imports/api/plants.js';

import '/imports/ui/components/no-search-results.js';

Template.results.helpers({
  results() {
    return Plants.find();
  },

  settings() {
    return {
      noDataTmpl:     Template.noSearchResults,
      showRowCount:   true,
      useFontAwesome: true,
      fields: [
        {
          key:   'scientificName',
          label: 'Scientific Name'
        }, {
          key:   'family',
          label: 'Family'
        }, {
          key:   'recordedBy',
          label: 'Recorded By'
        }
      ]
    };
  }
});
