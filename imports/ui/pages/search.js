import './search.html';

import { ReactiveVar } from 'meteor/reactive-var';
import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { $ } from 'meteor/jquery';

import { Plants } from '/imports/api/plants.js';

Template.search.onCreated(() => {
  Template.instance().query = new ReactiveVar(false);
});

Template.search.helpers({
  resultsExist() {
    return Template.instance().query.get();
  },

  plantResults() {
    // Make this a variable so later adjustments do not need to
    // be done multiple times. Unless you're into that...
    const regex = {
      $regex:   Template.instance().query.get(),
      $options: 'i'
    };

    return results = Plants.find({
      $or: [
        { family:         regex },
        { recordedBy:     regex },
        { scientificName: regex }
      ]
    });
  },

  tableSettings() {
    return {
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

Template.search.onRendered(() => {
  // Upon full subscription, remove disablement.
  FlowRouter.subsReady('subscribePlants', () => {
    $('#form-search [disabled]').removeAttr('disabled');
  });
});

Template.search.events({
  'submit #form-search'(event, instance) {
    const $search = $('#search');

    // De-focus from the search button, visuals are nice.
    if ($('#form-search button').is(':focus')) {
      $('#form-search button').blur();
    }

    event.preventDefault();

    // No point in searching the same thing over again.
    if ($search.data('search-query') == $search.val().trim()) {
      return;
    }

    // Set the new value.
    $search.data('search-query', $search.val().trim());

    // If the search is blank, remove the table.
    if ($search.val().trim() == '') {
      instance.query.set(false);

      return;
    }

    // Start searching content for the table.
    instance.query.set($search.val().trim());
  }
});
