/**
 * Attach event when document is ready!
 */
(function($) {
	$(function() {

		/**
		 * Attach event to the drop down menu
		 */
		$('#tx-infinitescrollgallery-category').change(function(event) {

			// Reset variable
			//infinitesSrollGallery.numberOfScroll = 0;
			//$('#tx-infinitescrollgallery-offset').val(0);
			//$('#tx-infinitescrollgallery-form').submit();

			// Empty image stack before loading
			//$('#tx-infinitescrollgallery-recordnumber').hide();
		});


		/**
		 * Attach event to the search field
		 */
		$('#tx-infinitescrollgallery-searchTerm').keydown(function(event) {

			// True when key 'enter' hit
			//if (event.keyCode == 13) {
			//
			//	// Reset variable
			//	infinitesSrollGallery.numberOfScroll = 0;
			//	$('#tx-infinitescrollgallery-offset').val(0);
			//	$('#tx-infinitescrollgallery-form').submit();
			//
			//	// Empty image stack before loading
			//	$('.yoxview ul').html('');
			//	$('#tx-infinitescrollgallery-recordnumber').hide();
			//
			//	event.preventDefault();
			//}
		});

		// Function scroll to load new images when scrolling down
		$(window).scroll(function() {
			// "enableMoreLoading" is a setting coming from the BE bloking / enabling dynamic loading of thumbnail
			if (infinitesSrollGallery.enableMoreLoading && $(window).scrollTop() >= $(document).height() - $(window).height() - 10) {

				// Computes if there are more images to display and an ajax request can be sent against the server
				var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
				var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());
				var moreImagesToLoad = numberOfVisibleImages < totalImages;

				if (infinitesSrollGallery.ajaxReady && moreImagesToLoad) {

					//infinitesSrollGallery.numberOfScroll++;
					//$('.tx-infinitescrollgallery-next a').hide();
					//var limit = $('#tx-infinitescrollgallery-limit').val();
					//var offset = parseInt(limit * infinitesSrollGallery.numberOfScroll);
					//
					//// load new set of images
					//$('#tx-infinitescrollgallery-offset').val(offset);
					//
					//// Fix potential bug if placeholder is not supported
					//if ($('#tx-infinitescrollgallery-searchTerm').attr('value') == $('#tx-infinitescrollgallery-searchTerm').attr('placeholder')) {
					//	$('#tx-infinitescrollgallery-searchTerm').attr('value', '');
					//}
					//$('#tx-infinitescrollgallery-form').submit()
				}
			}
		});

		var $target = $('#infiniteScrollGallery');

		for (var i = 0; i < infinitesSrollGallery.images.length; i++) {
            var image = infinitesSrollGallery.images[i];
            $target.append('<li><a data-width="' + image.width + '" data-height="' + image.height + '" href="' + image.enlarged + '" style="background-image:url(' + image.thumbnail + ');"></a></li>');
        }

        var container = [];
        $target.find('li').each(function() {
            var $link = $(this).find('a');
            var item = {
                src: $link.attr('href'),
                w: $link.data('width'),
                h: $link.data('height'),
                title: $link.data('caption')
            };

            container.push(item);
        });

		$target.find('a').click(function(event) {
			event.preventDefault();

			var $pswp = $('.pswp')[0];
			var options = {
				index: $(this).parent('li').index(),
				bgOpacity: 0.85,
				showHideOpacity: true
			};

			var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
			gallery.init();
		});

	});
})(jQuery);
