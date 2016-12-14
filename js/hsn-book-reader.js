/**
 * hsn-book-reader.js v1.0.0
 * Copyright (c) 2016. Center for Digital Humanities.
 * Licensed under MIT.
 */

/**
 * Constructor
 */
function ManuscriptViewer() {
  // Assure it's interpreted as an integer before `getPageHeight()` and
  // `getPageWidth()`. It does not matter for `$.ajax()`.
  this.pointer    = parseInt($('#BookReader').data('pointer'), 10);
  this.collection = $('#BookReader').data('collection');

  // Request data without any need for callbacks.
  // XMLHttpRequest, $.ajax, and $.getJSON does not work for localhost.
  const ajax = $.ajax('includes/manuscript-viewer-helper.php?pointer=' + this.pointer + '&collection=' + this.collection, {
    async: false
  });

  // Initialize class-wide variables.
  this.data   = JSON.parse(ajax.responseText);
  this.reader = new BookReader();

  // Assign class-wide variables of class-wide variable.
  this.reader.numLeafs      = this.data.pages;
  this.reader.bookTitle     = this.data.title;
  this.reader.imagesBaseURL = '../img/bookreader/';
}

/**
 * Function Initialization
 *
 * Initialize functionality for the BookReader object.
 */
ManuscriptViewer.prototype.functions = function () {
  const self = this;

  this.reader.getEmbedCode = function (width, height, params) {
    return 'Embed code not supported in book reader demo.';
  };

  /**
   * Page Height
   *
   * Returns the height of the page of the given index.
   *
   * @param  {Integer} index -- Index of the book.
   * @return {Integer}
   */
  this.reader.getPageHeight = function (index) {
    return self.data.images[index].height;
  };

  this.reader.getPageNum = function (index) {
    return index + 1;
  };

  /**
   * Page Side
   *
   * Determines if the page index is even or odd. If it is even, it is on
   * the left, otherwise, it is on the right.
   *
   * Originally, it was programmed the other way around. This was done with
   * the intention of having a book cover be first. Since these do not have
   * book covers (yet, or if ever), it is done this way.
   *
   * @param  {Integer} index -- The page index.
   * @return {String}
   */
  this.reader.getPageSide = function (index) {
    if (index % 2 === 0) {
      return 'L';
    } else {
      return 'R';
    }
  };

  /**
   * Page URL
   *
   * Returns the URL of dynamic parameters based on the given index.
   *
   * @param  {Integer} index -- Index of the book.
   * @return {String}
   */
  this.reader.getPageURI = function (index) {
    const url     = 'http://digital.tcl.sc.edu/utils/ajaxhelper/?action=2&CISOROOT=' + self.collection;
    const width   = '&DMWIDTH='  + this.getPageWidth(index);
    const height  = '&DMHEIGHT=' + this.getPageHeight(index);
    const pointer = '&CISOPTR='  + (index + self.pointer);

    return url + pointer + width + height;
  };

  /**
   * Page Width
   *
   * Returns the width of the page of the given index.
   *
   * @param  {Integer} index -- Index of the book.
   * @return {Integer}
   */
  this.reader.getPageWidth = function (index) {
    return self.data.images[index].width;
  };

  this.reader.getSpreadIndices = function (index) {
    let spreadIndices = [null, null];

    if ('rl' == this.pageProgression) {
      // Right to Left
      if (this.getPageSide(index) == 'R') {
        spreadIndices[1] = index;
        spreadIndices[0] = index + 1;
      } else {
        // Given index was LHS
        spreadIndices[0] = index;
        spreadIndices[1] = index - 1;
      }
    } else {
      // Left to right
      if (this.getPageSide(index) == 'L') {
        spreadIndices[0] = index;
        spreadIndices[1] = index + 1;
      } else {
        // Given index was RHS
        spreadIndices[1] = index;
        spreadIndices[0] = index - 1;
      }
    }

    return spreadIndices;
  };
};

/**
 * Adjustment.
 *
 * Adjusts the visuals to a more appealing look, without rewriting the
 * library code.
 */
ManuscriptViewer.prototype.adjustVisuals = function () {
  // Remove unnecessary items.
  $('#BRtoolbarbuttons, .BRicon.book_left, .BRicon.book_right, .BRicon.onepg, .BRicon.twopg, .BRicon.thumb').remove();

  // Adjust the title.
  $('#BRreturn')
    .html($('#BRreturn a').text())
    .css({
      width:        '100%',
      display:      'block',
      'text-align': 'center'
    });

  // Add a back button.
  $('<a href="browse-viewer.php" class="btn btn-sm btn-default back-button">Back</a>').insertAfter($('#BRtoolbar > span:first-child'));

  // Add an inactivity timer.
  let timeout = setTimeout(function () {
    window.location = 'browse-viewer.php';
  }, 300000);

  $(document).on('click mousemove', function () {
    clearTimeout(timeout);

    timeout = setTimeout(function () {
      window.location = 'browse-viewer.php';
    }, 300000);
  });
};

/**
 * Accessors
 */
ManuscriptViewer.prototype.getReader = function () {
  return this.reader;
};

/**
 * Mutators
 */
ManuscriptViewer.prototype.setReader = function (reader) {
  this.reader = reader;
};

const viewer = new ManuscriptViewer();
viewer.functions();
viewer.getReader().init();
viewer.adjustVisuals();
