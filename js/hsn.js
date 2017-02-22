/**
 * hsn.js v1.0.0
 * Copyright (c) 2016. Center for Digital Humanities.
 * Licensed under MIT.
 */

// Assign all DataTables.
$('[data-plugin="dataTable"]').dataTable();

// TODO - Production: Remove this when the respective image is gone.
$('#hideImage').click(function (event) {
  if ($('#galleryImage').is(':hidden')) {
    $(this).text('Hide Image');
    $('#galleryImage').show();
  } else {
    $(this).text('Show Image');
    $('#galleryImage').hide();
  }
});
