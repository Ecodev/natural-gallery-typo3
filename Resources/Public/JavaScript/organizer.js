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
function organize(gallery) {

    var galleries = gallery ? [gallery] : infinitesScrollGallery;

    _.forEach(galleries, function(gallery) {
        if (gallery.thumbnailFormat == 'natural' ) {
            organizeNatural(gallery.bodyElement, gallery.height, gallery.margin, gallery.round);
        } else if (gallery.thumbnailFormat == 'square') {
            organizeSquare(gallery.bodyElement, gallery.imagesPerRow,  gallery.margin, gallery.round);
        } else {
            organizeNatural(gallery.bodyElement, gallery.height, gallery.margin, gallery.round);
        }
    });
}

function organizeNatural(body, maxRowHeight, margin, round) {

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
    organizeRow(fullWidth, maxRowHeight, margin, elements, round);
}

function organizeSquare(body, nbPictPerRow, margin, round) {

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

    applySquaredStyle(Math.floor(size), margin, elements, nbPictPerRow, round);
}


function organizeRow(fullWidth, maxRowHeight, margin, elements, round) {
    for (var chunkSize = 0; chunkSize < elements.length; chunkSize++) {
        var chunk = elements.slice(0, chunkSize);
        var rowWidth = getRowWidth(maxRowHeight, margin, chunk);
        if (rowWidth >= fullWidth) {
            var rowHeight = getRowHeight(fullWidth, margin, chunk);
            var newRowWidth = getRowWidth(rowHeight, margin, chunk);
            applyStyle(fullWidth, newRowWidth, rowHeight, margin, chunk, round);
            organizeRow(fullWidth, maxRowHeight, margin, elements.slice(chunkSize), round);
            break;
        }
    }
}

function getRowWidth(maxRowHeight, margin, elements) {
    return margin * (elements.length - 1) + getRatios(elements) * maxRowHeight;
}

function getRowHeight(fullWidth, margin, elements) {
    return fullWidth / getRatios(elements) + margin * (elements.length - 1);
}

function getRatios(elements) {
    var totalWidth = 0;

    elements.each(function(index, el) {
        totalWidth += getImageRatio(el);
    });

    return totalWidth;
}

function getImageRatio(el) {
    el = $(el);
    return Number(el.attr('data-width')) / Number(el.attr('data-height'));
}


function applySquaredStyle(size, margin, elements, nbPictPerRow, round) {

    elements.each(function(index, el) {
        el = $(el);
        el.css('width', size)
          .css('height', size);

        var figure = el.parent();
        figure.css('width', size)
              .css('height', size)
              .css('margin-bottom', margin)
              .css('margin-right', margin);

        setImageSize(el, size, size, margin, round, index % nbPictPerRow === nbPictPerRow - 1);

    });
}

function applyStyle(fullWidth, rowWidth, height, margin, elements, round) {

    var excess = apportionExcess(fullWidth, rowWidth, elements);

    var lost = 0;
    elements.each(function(index, el) {

        var width = getImageRatio(el) * height - excess;
        lost += width - Math.floor(width);
        width = Math.floor(width);

        if (lost >= 1 || index === elements.length - 1 && Math.round(lost) === 1) {
            width++;
            lost--;
        }

        setImageSize(el, width, height, margin, round, index == elements.length - 1);
    });
}

function setImageSize(el, width, height, margin, round, last) {

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
}

function apportionExcess(fullWidth, rowWidth, elements) {
    var excess = rowWidth - fullWidth;
    var excessPerItem = excess / elements.length;
    return excessPerItem
}
