import './search.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { $ } from 'meteor/jquery';

Template.search.helpers({
  query() {
    return FlowRouter.getParam('query');
  },

  // TODO: Determine if this should be renamed to `isGeodude`
  isRockType() {
    return FlowRouter.getParam('type') === undefined || FlowRouter.getParam('type') === 'rocks';
  }
});

Template.search.events({
  'submit #form-search'(event) {
    event.preventDefault();

    // If we search for nothing, then what is the point of searching?
    if ($('#search').val().trim() === '') {
      return;
    }

    // This'll make the URL /search/undefined/:query and who likes that?
    if ($('[name="collection"]:checked').length === 0) {
      return;
    }

    FlowRouter.go('/search/' + $('[name="collection"]:checked').val() + '/' + $('#search').val().trim());
  }
});
