import { Meteor } from 'meteor/meteor';
import { _ } from 'meteor/underscore';

import { Content } from '/imports/api/content.js';
import { Plants } from '/imports/api/plants.js';

Meteor.startup(function () {
  /**
   * Updates the database in the instance that the local file has a different value.
   *
   * @param {JSON Object}      file       - The local file's JSON object.
   * @param {Mongo.Document}   database   - The database's document.
   * @param {Mongo.Collection} Collection - The collection to possibly be updated.
   */
  const updateCollectionItem = (file, database, Collection) => {
    let setter = {};

    // Iterate through the file.
    for (let key in file) {
      // Always assume the file is the correct version.
      if (file[key] !== database[key]) {
        // Store temporarily.
        setter[key] = file[key];
      }
    }

    if (Object.getOwnPropertyNames(setter).length) {
      console.log('Collection Update Detected. Object:', setter);

      Collection.update({
        _id: database._id
      }, {
        $set: setter
      });
    }
  };

  // Iterate through the CONTENTdm cached file.
  _.each(JSON.parse(Assets.getText('contentdm-data.json')), (content) => {
    const item = Content.findOne({ pointer: content.pointer });

    if (item) {
      updateCollectionItem(content, item, Content)
    } else {
      console.log('Content Insert Detected. Pointer: ' + content.pointer);
      Content.insert(content);
    }
  });
});
