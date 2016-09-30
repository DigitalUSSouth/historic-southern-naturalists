import { Meteor } from 'meteor/meteor';
import { Mongo } from 'meteor/mongo';
import { check } from 'meteor/check';

export const Content = new Mongo.Collection('contentdm');

if (Meteor.isServer) {
  Meteor.publish('contentdm-search', function (query) {
    check(query, String);

    const regex = {
      $regex:   query,
      $options: 'i'
    };

    return Content.find({
      $or: [
        { contri: regex },
        { date:   regex },
        { covera: regex },
        { descri: regex },
        { publis: regex },
        { relati: regex },
        { subjec: regex },
        { title:  regex }
      ]
    });
  });

  Meteor.publish('contentdm-viewer', function (pointer) {
    check(pointer, String);

    return Content.find({
      pointer: pointer
    });
  });
}

Content.deny({
  insert() { return false; },
  remove() { return false; },
  update() { return false; }
});
