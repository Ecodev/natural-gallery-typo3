/**
 * Based on http://blog.vjeux.com/2012/image/image-layout-algorithm-google-plus.html
 *
 * First, compute the number of pictures per row, based on target height (maxRowHeight).
 * Then compute the final height according to full container inner width
 *
 * @param maxRowHeight
 * @param margin
 * @param body
 */

var tx_infiniteScrollGallery_organizer = {

    organize: function(gallery) {

        var galleries = gallery ? [gallery] : infinitesScrollGallery;
        var self = this;

        _.forEach(galleries, function(gallery) {
            if (gallery.thumbnailFormat == 'natural') {
                self.organizeNatural(gallery.bodyElement, gallery.thumbnailMaximumHeight, gallery.margin, gallery.round);
            }
            else if(gallery.thumbnailFormat == 'square') {
                self.organizeSquare(gallery.bodyElement, gallery.imagesPerRow, gallery.margin, gallery.round);
            }
            else {
                self.organizeNatural(gallery.bodyElement, gallery.thumbnailMaximumHeight, gallery.margin, gallery.round);
            }
        });
    },

    organizeNatural: function(body, maxRowHeight, margin, round) {

        if (!margin) {
            margin = 0;
        }

        if (!maxRowHeight) {
            maxRowHeight = 300;
        }

        if (!round) {
            round = 0;
        }

        var fullWidth = body.innerWidth();
        var elements = body.find('a');
        this.organizeRow(fullWidth, maxRowHeight, margin, elements, round);
    },

    organizeSquare: function(body, nbPictPerRow, margin, round) {

        if (!margin) {
            margin = 1;
        }

        if (!nbPictPerRow) {
            nbPictPerRow = 4;
        }

        if (!round) {
            round = 0;
        }

        var fullWidth = body.innerWidth();
        var elements = body.find('a');

        var size = (fullWidth - (nbPictPerRow - 1) * margin) / nbPictPerRow;

        this.applySquaredStyle(Math.floor(size), margin, elements, nbPictPerRow, round);
    },

    organizeRow: function(fullWidth, maxRowHeight, margin, elements, round) {
        for (var chunkSize = 0; chunkSize < elements.length; chunkSize++) {
            var chunk = elements.slice(0, chunkSize);
            var rowWidth = this.getRowWidth(maxRowHeight, margin, chunk);
            if (rowWidth >= fullWidth) {
                var rowHeight = this.getRowHeight(fullWidth, margin, chunk);
                var newRowWidth = this.getRowWidth(rowHeight, margin, chunk);
                this.applyStyle(fullWidth, newRowWidth, rowHeight, margin, chunk, round);
                this.organizeRow(fullWidth, maxRowHeight, margin, elements.slice(chunkSize), round);
                break;
            }
        }
    },

    getRowWidth: function(maxRowHeight, margin, elements) {
        return margin * (elements.length - 1) + this.getRatios(elements) * maxRowHeight;
    },

    getRowHeight: function(fullWidth, margin, elements) {
        return fullWidth / this.getRatios(elements) + margin * (elements.length - 1);
    },

    getRatios: function(elements) {

        var self = this;
        var totalWidth = 0;

        elements.each(function(index, el) {
            totalWidth += self.getImageRatio(el);
        });

        return totalWidth;
    },

    getImageRatio: function(el) {
        el = $(el);
        return Number(el.attr('data-width')) / Number(el.attr('data-height'));
    },

    applySquaredStyle: function(size, margin, elements, nbPictPerRow, round) {
        var self = this;
        elements.each(function(index, el) {
            el = $(el);
            el.css('width', size)
              .css('height', size);

            var figure = el.parent();
            figure.css('width', size)
                  .css('height', size)
                  .css('margin-bottom', margin)
                  .css('margin-right', margin);

            self.setImageSize(el, size, size, margin, round, index % nbPictPerRow === nbPictPerRow - 1);

        });
    },

    applyStyle: function(fullWidth, rowWidth, height, margin, elements, round) {

        var self = this;
        var excess = this.apportionExcess(fullWidth, rowWidth, elements);

        var lost = 0;
        elements.each(function(index, el) {

            var width = self.getImageRatio(el) * height - excess;
            lost += width - Math.floor(width);
            width = Math.floor(width);

            if (lost >= 1 || index === elements.length - 1 && Math.round(lost) === 1) {
                width++;
                lost--;
            }

            self.setImageSize(el, width, height, margin, round, index == elements.length - 1);
        });
    },

    setImageSize: function(el, width, height, margin, round, last) {

        el = $(el);
        var parent = el.parent();
        el.css('width', width)
          .css('height', Math.floor(height));

        parent.css('width', width)
              .css('height', Math.floor(height))
              .css('margin-bottom', margin)
              .css('margin-right', margin);

        if (last) {
            parent.css('margin-right', 0)
        }

        if (round) {
            el.css('border-radius', round);
        }
    },

    apportionExcess: function(fullWidth, rowWidth, elements) {
        var excess = rowWidth - fullWidth;
        var excessPerItem = excess / elements.length;
        return excessPerItem
    }
};
