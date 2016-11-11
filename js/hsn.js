/**
 * hsn.js v1.0.0
 * Copyright (c) 2016. Center for Digital Humanities.
 * Licensed under MIT.
 */

$(document).ready(function () {
  if (window.location.pathname.indexOf('search') > -1 && $('div[role="tabpanel"] > table').length) {
    $('div[role="tabpanel"] > table').dataTable();
  }
});
