import './search.html';

import { FlowRouter } from 'meteor/kadira:flow-router';
import { Template } from 'meteor/templating';
import { $ } from 'meteor/jquery';

Template.search.helpers({
  query() {
    return FlowRouter.getParam('query');
  }
});

Template.search.events({
  'submit #form-search'(event) {
    event.preventDefault();

    if ($('#search').val().trim() === '') {
      return;
    }

    FlowRouter.go('/search/' + $('#search').val().trim());
  }
});
