/**
 * Attach event when document is ready!
 */
(function($) {
    $(function() {

        /**
         * Used to test if we scroll direction
         * Avoid to load more images when scrolling up in the detection zone
         * @type {number}
         */
        var old_scroll_top = 0;

        /**
         * Photoswipe global template element (dom element)
         */
        var $pswp = $('.pswp')[0];

        /**
         * Photoswipe javascript object
         * Contains api to interact with library
         * @type PhotoSwipe
         */
        var pswp = null;

        /**
         * Default rows by step
         * @type {number}
         */
        var minNumberOfRowsAtStart = 2;

        var organizer = tx_infiniteScrollGallery_organizer;

        function initGallery() {

            for (var i = 0; i < infinitesScrollGallery.length; i++) {
                var gallery = infinitesScrollGallery[i];
                gallery.pswpContainer = [];
                gallery.bodyElement = $('#tx-infinitescrollgallery-main-' + gallery.id).find('.tx-infinitescrollgallery-body');
                organizer.organize(gallery);
                addElements(gallery);
            }
        }

        $(window).on('resize', _.debounce(function() {
            organizer.organize(null, resize);
        }, 200));

        function resize() {
            for (var i = 0; i < infinitesScrollGallery.length; i++) {
                var gallery = infinitesScrollGallery[i];
                var nbRows = gallery.images[gallery.pswpContainer.length - 1].row + 1;
                resetElements(gallery);
                addElements(gallery, nbRows);
            }
        }

        function addElements(gallery, rows) {

            if (!gallery) {
                gallery = getGallery();
            }

            if (!rows) {
                rows = getDefaultPageSize(gallery);
            }

            var nextImage = gallery.pswpContainer.length;
            var lastRow = gallery.pswpContainer.length ? gallery.images[nextImage].row + rows : rows;

            // Select next elements, comparing their rows
            for (var i = nextImage; i < gallery.images.length; i++) {
                var element = gallery.images[i];
                if (element.row < lastRow) {

                    // Add enlarged to Photoswipe gallery
                    gallery.pswpContainer.push({
                        src: element.enlarged,
                        w: element.eWidth,
                        h: element.eHeight,
                        title: element.title
                    });

                    // Transform in DOM elements and store it
                    var figure = getFigure(element, gallery);
                    element.figure = figure;

                    gallery.bodyElement.append(figure.figure);

                    bindClick(figure.image, gallery);
                    styleFigure(element, gallery, element.row === lastRow + 1);
                }
            }
        }

        function getDefaultPageSize(gallery) {

            if (gallery.limit) {
                return gallery.limit;
            }

            var winHeight = $(window).height();
            var top = gallery.bodyElement.offset().top;
            var galleryVisibleHeight = winHeight - top;
            var maxRowHeight = gallery.thumbnailMaximumHeight;
            var nbRows = Math.ceil(galleryVisibleHeight / maxRowHeight);

            return nbRows < minNumberOfRowsAtStart ? minNumberOfRowsAtStart : nbRows;
        }

        function getFigure(element, gallery) {

            var $figure = $('<figure></figure>');
            var $image = $('<a></a>')
                .css('background-image', 'url(' + element.thumbnail + ')')
                .attr('href', element.enlarged);

            if (gallery.round) {
                $image.css('border-radius', gallery.round)
            }

            $figure.append($image);

            return {
                figure: $figure,
                image: $image
            };
        }

        function styleFigure(element, gallery, hide) {

            element.figure.figure
                   .css('width', element.width)
                   .css('height', element.height)
                   .css('margin-right', gallery.margin)
                   .css('margin-bottom', gallery.margin);

            if (element.last) {
                element.figure.figure.css('margin-right', 0);
            }

            if (hide) {
                element.figure.figure.hide();
            }

            element.figure.image
                   .css('display', 'none')
                   .css('width', element.width)
                   .css('height', element.height);

            element.figure.image.fadeIn({duration: 1000});
        }

        function bindClick(image, gallery) {

            image.on('click', function(e) {
                e.preventDefault();

                var self = this;
                var options = {
                    index: $(this).parent('figure').index(),
                    bgOpacity: 0.85,
                    showHideOpacity: true,
                    loop: false
                };

                pswp = new PhotoSwipe($pswp, PhotoSwipeUI_Default, gallery.pswpContainer, options);
                pswp.init();

                // Loading one more page when going to next image
                pswp.listen('beforeChange', function(delta) {
                    // Positive delta indicates "go to next" action, we don't load more objects on looping back the gallery (same logic when scrolling)
                    if (delta > 0 && pswp.getCurrentIndex() == pswp.items.length - 1) {
                        addElements(getGallery(self));
                    }
                });
            });
        }

        function resetElements(gallery) {
            gallery.pswpContainer = [];
            gallery.bodyElement.html('');
        }

        function getGallery(element) {

            var gallery = infinitesScrollGallery[0];
            if (element) {
                var galleryId = $(element).parents('.tx-infinitescrollgallery').data('galleryid');
                gallery = _.find(infinitesScrollGallery, {id: Number(galleryId)});
            }
            return gallery;
        }

        /**
         * Attach event to the drop down menu
         */
        $('#tx-infinitescrollgallery-category').change(function(event) {

            // Reset variable
            //infinitesScrollGallery.numberOfScroll = 0;
            //$('#tx-infinitescrollgallery-offset').val(0);
            //$('#tx-infinitescrollgallery-form').submit();

            // Empty image stack before loading
            //$('#tx-infinitescrollgallery-recordnumber').hide();
        });

        /**
         * Attach event to next button
         */
        $('.tx-infinitescrollgallery-next a').on('click', function(e) {
            e.preventDefault();
            addElements(getGallery(this));
        });

        /**
         * Attach event to the search field
         */
        $('.tx-infinitescrollgallery-searchTerm').keydown(function(event) {
            // True when key 'enter' hit
            if (event.keyCode == 13) {
                event.preventDefault();
                resetElements(getGallery(this));
            }
        });

        // Function scroll to load new images when scrolling down
        // Allow scroll if there is only a single gallery on page and moreLoading allowed
        if (infinitesScrollGallery.length == 1 && getGallery().limit === 0) {

            $('.tx-infinitescrollgallery-next').hide();

            $(document).scroll(function() {

                var gallery = getGallery().bodyElement;

                var endOfGalleryAt = gallery.offset().top + gallery.height() - $(window).height() + 150;

                // Avoid to expand gallery if we are scrolling up
                var current_scroll_top = $(document).scrollTop();
                var scroll_delta = current_scroll_top - old_scroll_top;
                old_scroll_top = current_scroll_top;

                // "enableMoreLoading" is a setting coming from the BE bloking / enabling dynamic loading of thumbnail
                if (scroll_delta > 0 && $(window).scrollTop() > endOfGalleryAt) {

                    // Computes if there are more images to display and an ajax request can be sent against the server
                    //var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
                    //var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());

                    addElements(getGallery(), 1);
                }
            });
        }

        initGallery();

    });
})(jQuery);
