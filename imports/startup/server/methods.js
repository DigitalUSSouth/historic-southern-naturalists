import { Meteor } from 'meteor/meteor';
import { check } from 'meteor/check';
import { HTTP } from 'meteor/http';

Meteor.methods({
  'search'(query) {
    check(query, String);

    return Meteor.call('q', 'dmQuery/hsn/CISOSEARCHALL^' + query + '^any/title!descri!contri/title/100/1/0/0/0/0/0/0/json');
  },

  'q'(param) {
    // Allow other methods on the same connection to run.
    this.unblock();

    return HTTP.get('http://digital.tcl.sc.edu:81/dmwebservices/index.php', {
      params: {
        q: param
      }
    });
  }
});
