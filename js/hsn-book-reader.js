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
  this.pointer = parseInt($('#BookReader').attr('data-pointer'), 10);

  // Request data without any need for callbacks.
  // XMLHttpRequest, $.ajax, and $.getJSON does not work for localhost.
  const ajax = $.ajax('includes/manuscript-viewer-helper.php?pointer=' + this.pointer, {
    async: false
  });

  // Initialize class-wide variables.
  this.data   = JSON.parse(ajax.responseText);
  this.reader = new BookReader();

  // Assign class-wide variables of class-wide variable.
  this.reader.numLeafs      = this.data.images.length;
  this.reader.bookTitle     = this.data.title;
  this.reader.imagesBaseURL = '../img/bookreader/';
}

/**
 * Function Initialization
 *
 * Initialize functionality for the BookReader object.
 */
ManuscriptViewer.prototype.functions = function () {
  var self = this;

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
    if (index === undefined) {
      return;
    }

    return self.data.images[index + self.pointer].height;
  };

  this.reader.getPageNum = function (index) {
    return index + 1;
  };

  this.reader.getPageSide = function (index) {
    if (0 == (index & 0x1)) {
      return 'R';
    } else {
      return 'L';
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
    return 'http://digital.tcl.sc.edu/utils/ajaxhelper/?action=2&CISOROOT=hsn&CISOPTR=' + (index + self.pointer) + '&DMWIDTH=' + this.getPageWidth() + '&DMHEIGHT=' + this.getPageHeight();
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
    if (index === undefined) {
      return;
    }

    return self.data.images[index + self.pointer].width;
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
