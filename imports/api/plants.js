import { Meteor } from 'meteor/meteor';
import { Mongo } from 'meteor/mongo';

export const Plants = new Mongo.Collection('plants');

if (Meteor.isServer) {
  Meteor.publish('plants', function () {
    return Plants.find();
  });
}

Plants.deny({
  insert() { return false; },
  remove() { return false; },
  update() { return false; }
});
