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
      // Iterate through the file.
      let setter = {};

      for (let key in plant) {
        // Always assume the file is the correct version vs the database.
        if (current[key] !== plant[key]) {
          // Store temporarily.
          setter[key] = plant[key];
        }
      }

      // If there's something new, update the database.
      if (Object.getOwnPropertyNames(setter).length) {
        // Queue the database once so we don't have to download more RAM.
        Plants.update({
          _id: plant._id,
           id: plant.id
        }, {
          $set: setter
        });

        console.log('Updated:', plant.id);
      }
    } else {
      Plants.insert(plant);
    }
  });

  console.log('Startup:', new Date());
});
