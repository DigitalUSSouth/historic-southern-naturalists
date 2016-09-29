import { Meteor } from 'meteor/meteor';
import { _ } from 'meteor/underscore';

import { Plants } from '/imports/api/plants.js';

Meteor.startup(function () {
  console.log('Initial:', new Date());

  // Every. Single. Time. This server starts, we update.
  // This could become a problem...
  _.each(JSON.parse(Assets.getText('occurrences.json')), function (plant) {
    const current = Plants.findOne({ id: plant.id });

    if (current) {
      updateDocument(plant, current, Plants, {
        _id: plant._id,
         id: plant.id
      });
    } else {
      Plants.insert(plant);
    }
  });

  console.log('Startup:', new Date());
});

function updateDocument(incoming, database, collection, update) {
  // Iterate through the file.
  let setter = {};

  for (let key in incoming) {
    // Always assume the file is the correct version vs the database.
    if (incoming[key] !== database[key]) {
      // Store temporarily.
      setter[key] = incoming[key];
    }
  }

  // If there's something new, update the database.
  if (Object.getOwnPropertyNames(setter).length) {
    collection.update(update, {
      $set: setter
    });
  }
};
