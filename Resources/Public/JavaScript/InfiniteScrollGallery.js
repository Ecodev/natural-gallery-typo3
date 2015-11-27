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

        function initGallery() {
            infinitesScrollGallery.forEach(function(gallery) {
                gallery.pswp = $pswp;
                gallery.container = [];
                gallery.bodyElement = $('#tx-infinitescrollgallery-main-' + gallery.id).find('.tx-infinitescrollgallery-body');
                addElements(gallery);
            });
		}

		function addElements(gallery, number) {

			if (!number) {
				number = defaultImagesByPage;
			}

			// Get elements already in the gallery
			var start = gallery.container.length;

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
					w: image.width,
					h: image.height,
					title: image.title
				};

				gallery.container.push(item);

				bindClick(figure.image);
			});

		}

		function getFigure(image) {

			var $figure = $('<figure></figure>');
			var $image = $('<a></a>')
                .css('background-image', 'url(' + image.thumbnail+ ')')
                .css('display', 'none')
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

                pswp = new PhotoSwipe($pswp, PhotoSwipeUI_Default, getGallery(self).container, options);
                pswp.init();
                pswp.listen('beforeChange', function(delta) {
                    // Positive delta indicates "next" action, we don't load more objects on looping back the gallery
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
            var galleryId = $(element).parents('.tx-infinitescrollgallery').data('galleryid');
            var gallery = _.find(infinitesScrollGallery, {id: Number(galleryId)});
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
        if (infinitesScrollGallery.length == 1 && infinitesScrollGallery[0].enableMoreLoading) {

            $('.tx-infinitescrollgallery-next').hide();

            $(window).scroll(function() {

                var current_scroll_top = $(document).scrollTop();
                var scroll_delta = current_scroll_top - old_scroll_top;
                old_scroll_top = current_scroll_top;

                // "enableMoreLoading" is a setting coming from the BE bloking / enabling dynamic loading of thumbnail
                if (scroll_delta > 0 && $(window).scrollTop() >= $(document).height() - $(window).height() - 10) {

                    // Computes if there are more images to display and an ajax request can be sent against the server
                    var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
                    var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());

                    addElements(1);
                    window.setTimeout(function() {
                        addElements(1);
                    }, 300);
                    window.setTimeout(function() {
                        addElements(1);
                    }, 600);
                    window.setTimeout(function() {
                        addElements(1);
                    }, 900);

                }
            });
        }

		initGallery();

	});
})(jQuery);
