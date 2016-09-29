import { Meteor } from 'meteor/meteor';
import { check } from 'meteor/check';
import { HTTP } from 'meteor/http';

Meteor.methods({
  'search'(query) {
    check(query, String);

    return Meteor.call('q', 'dmQuery/hsn/CISOSEARCHALL^' + query + '^any/title!descri!contri/0/1024/0/0/0/0/0/1/json');
  },

  'info'(pointer) {
    check(pointer, String);

    return JSON.parse(Meteor.call('q', 'dmGetItemInfo/hsn/' + pointer + '/json').content);
  },

  'image'(pointer) {
    check(pointer, String);

    const info = JSON.parse(Meteor.call('ajaxhelper', pointer).content).imageinfo;

    return 'http://digital.tcl.sc.edu/utils/ajaxhelper/?action=2&CISOPTR=' + pointer + '&CISOROOT=hsn&DMWIDTH=' + info.width + '&DMHEIGHT=' + info.height;
  },

  'q'(param) {
    // Allow other methods on the same connection to run.
    this.unblock();

    return HTTP.get('http://digital.tcl.sc.edu:81/dmwebservices/', {
      params: {
        q: param
      }
    });
  },

  'ajaxhelper'(pointer) {
    // Allow other methods on the same connection to run.
    this.unblock();

    return HTTP.get('http://digital.tcl.sc.edu/utils/ajaxhelper/', {
      params: {
        CISOPTR:  pointer,
        CISOROOT: 'hsn'
      }
    });
  }
});
