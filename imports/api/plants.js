import { Meteor } from 'meteor/meteor';
import { Mongo } from 'meteor/mongo';
import { check } from 'meteor/check';

export const Plants = new Mongo.Collection('plants');

if (Meteor.isServer) {
  Meteor.publish('symbiota-search', function (query) {
    check(query, String);

    const regex = {
      $regex:   query,
      $options: 'i'
    };

    return Plants.find({
      collectionCode: 'ACM',
      $or: [
        { family:         regex },
        { identifiedBy:   regex },
        { scientificName: regex }
      ],
      scientificName: {
        $ne: ''
      }
    });
  });

  Meteor.publish('symbiota-viewer', function (id) {
    check(id, String);

    return Plants.find({
      id: id
    });
  });
}

Plants.deny({
  insert() { return false; },
  remove() { return false; },
  update() { return false; }
});
