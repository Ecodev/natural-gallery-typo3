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
		 * Default images by step
		 * @type {number}
         */
		var defaultImagesByPage = 12;

        var organizer = tx_infiniteScrollGallery_organizer;

        function initGallery() {
            infinitesScrollGallery.forEach(function(gallery) {
                gallery.pswpContainer = [];
                gallery.bodyElement = $('#tx-infinitescrollgallery-main-' + gallery.id).find('.tx-infinitescrollgallery-body');
                addElements(gallery, gallery.limit);
            });
        }

        window.addEventListener('resize', _.debounce(function() {
            organizer.organize();
        }, 200));

        function addElements(gallery, number) {

			if (!number) {
				number = defaultImagesByPage;
			}

			if (!gallery) {
				gallery = getGallery();
			}

			// Get elements already in the gallery
			var start = gallery.pswpContainer.length;

			// Select next elements
			var elementsToAdd = gallery.images.slice(start, start + number);

			// For each element to add
			elementsToAdd.forEach(function(image) {

				// Transform to dom elements
				var figure = getFigure(image);

				// Append figure (with link inside) to dom
				gallery.bodyElement.append(figure.figure);

                figure.image.fadeIn({duration: 1000});

				// Add element to gallery
				var item = {
					src: image.enlarged,
					w: image.eWidth,
					h: image.eHeight,
					title: image.title
				};

				gallery.pswpContainer.push(item);

				bindClick(figure.image);
			});

            organizer.organize(gallery);

		}

		function getFigure(image) {

			var $figure = $('<figure></figure>');
			var $image = $('<a></a>')
                .css('background-image', 'url(' + image.thumbnail+ ')')
                .css('display', 'none')
                .attr('data-width', image.tWidth)
                .attr('data-height', image.tHeight)
                .attr('href', image.enlarged);

			$figure.append($image);

			return {
				figure : $figure,
				image : $image
			};
		}

        function bindClick(image) {

            image.on('click', function(e) {
                e.preventDefault();

                var self = this;
                var options = {
                    index: $(this).parent('figure').index(),
                    bgOpacity: 0.85,
                    showHideOpacity: true,
                    loop:false
                };

                pswp = new PhotoSwipe($pswp, PhotoSwipeUI_Default, getGallery(this).pswpContainer, options);
                pswp.init();
                pswp.listen('beforeChange', function(delta) {
                    // Positive delta indicates "go to next" action, we don't load more objects on looping back the gallery (same logic when scrolling)
                    if (delta > 0 && pswp.getCurrentIndex() == pswp.items.length - 1) {
                        addElements(getGallery(self));
                    }
                });

            });
        }

		function resetElements(gallery) {
			gallery.container = [];
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

                    addElements(getGallery(), 4);
                }
            });
        }

		initGallery();

	});
})(jQuery);
