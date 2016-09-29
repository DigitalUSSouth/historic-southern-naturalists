import { Meteor } from 'meteor/meteor';
import { check } from 'meteor/check';
import { HTTP } from 'meteor/http';

Meteor.methods({
  /**
   * Search CONTENTdm for a specified query.
   *
   * @todo Split query based on spacing.
   *
   * @see https://www.oclc.org/support/services/contentdm/help/customizing-website-help/other-customizations/contentdm-api-reference/dmquery.en.html
   *
   * @param {String} query - The user-entered query.
   *
   * @return {JSON} The results of the query.
   */
  'search'(query) {
    check(query, String);

    return Meteor.call('q', 'dmQuery/hsn/CISOSEARCHALL^' + query + '^any/title!descri!contri/0/1024/0/0/0/0/0/1/json');
  },

  /**
   * Returns page-level information about an item.
   *
   * @see https://www.oclc.org/support/services/contentdm/help/customizing-website-help/other-customizations/contentdm-api-reference/dmgetiteminfo.en.html
   *
   * @param {String} pointer - The pointer of the item.
   *
   * @return {JSON} Information about the item.
   */
  'info'(pointer) {
    check(pointer, String);

    return JSON.parse(Meteor.call('q', 'dmGetItemInfo/hsn/' + pointer + '/json').content);
  },

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
   * Used on essentially all CONTENTdm API calls, this will
   * return a specified object, recommended to use JSON.
   *
   * @see https://www.oclc.org/support/services/contentdm/help/customizing-website-help/other-customizations/contentdm-api-reference.en.html
   *
   * @param {String} param - The API call.
   *
   * @return {Object} Optional to specify as either a XML or JSON object. Used for data interpretation.
   */
  'q'(param) {
    // Allow other methods on the same connection to run.
    this.unblock();

    return HTTP.get('http://digital.tcl.sc.edu:81/dmwebservices/', {
      params: {
        q: param
      }
    });
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
