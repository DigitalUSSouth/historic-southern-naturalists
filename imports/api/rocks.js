import { Meteor } from 'meteor/meteor';
import { Mongo } from 'meteor/mongo';
import { check } from 'meteor/check';

export const Rocks = new Mongo.Collection('rocks');

if (Meteor.isServer) {
  Meteor.publish('rock-search', function (query) {
    check(query, String);

    const regex = {
      $regex:   query,
      $options: 'i'
    };

    return Rocks.find({
      $or: [
        { title:       regex },
        { description: regex }
      ]
    });
  });

  Meteor.publish('rock-viewer', function (id) {
    return Rocks.find({
      _id: id
    });
  });
}

Rocks.deny({
  insert() { return false; },
  remove() { return false; },
  update() { return false; }
});
