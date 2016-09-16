import { Meteor } from 'meteor/meteor';
import { Mongo } from 'meteor/mongo';
import { check } from 'meteor/check';

export const Plants = new Mongo.Collection('plants');

if (Meteor.isServer) {
  Meteor.publish('plants', function (query) {
    check(query, String);

    const regex = {
      $regex:   query,
      $options: 'i'
    };

    return Plants.find({
      $or: [
        { family:         regex },
        { recordedBy:     regex },
        { scientificName: regex }
      ]
    });
  });

  Meteor.publish('plant-viewer', function (id) {
    return Plants.find({
      id: parseInt(id, 10)
    });
  })
}

Plants.deny({
  insert() { return false; },
  remove() { return false; },
  update() { return false; }
});
