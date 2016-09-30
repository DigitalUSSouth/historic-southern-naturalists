import { Meteor } from 'meteor/meteor';
import { check } from 'meteor/check';
import { HTTP } from 'meteor/http';

Meteor.methods({
  /**
   * Returns an image link for the rock-viewer page. However,
   * it first grabs the information of the image's dimension.
   *
   * @param {String} pointer - The pointer of the image.
   *
   * @return {String} The image link.
   */
  'image'(pointer) {
    check(pointer, String);

    // Since `dmGetImageInfo` will only return as an XML object, by removing the `action` parameter,
    // a JSON object returns. With this, it's easier to determine the width and height of the image.
    const info = JSON.parse(Meteor.call('ajaxhelper', pointer).content).imageinfo;

    return 'http://digital.tcl.sc.edu/utils/ajaxhelper/?action=2&CISOPTR=' + pointer + '&CISOROOT=hsn&DMWIDTH=' + info.width + '&DMHEIGHT=' + info.height;
  },

  /**
   * Returns a JSON object that tells the width and height
   * of an image based on its pointer.
   *
   * @param {String} pointer - The pointer of the image.
   *
   * @return {JSON}
   */
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
