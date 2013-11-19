///////////// RUNTIME VARIABLES /////////////
var infinitesSrollGallery = {
	ajaxReady: true,
	numberOfScroll: 0
};

/**
 * Attach event when document is ready!
 */
$(document).ready(function() {

	///////////// GALLERY /////////////
	// Set plugin configuration
	var slideShowConfiguration = {
		lang : $('#tx-infinitescrollgallery-language').val(),
		onEnd : function() {
			$.yoxview.close();
		},
		images: infinitesSrollGallery.imageStack,
		onSelect: function(imageIndex, image) {

			// "enableMoreLoading" is a setting coming from the BE blocking / enabling dynamic loading of thumbnail
			if (infinitesSrollGallery.enableMoreLoading){
				var images = $('.yoxview ul li');

				// Computes if there are more images to display and an ajax request can be sent against the server
				var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
				var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());
				var moreImagesToLoad =  numberOfVisibleImages < totalImages;

				// Determines if the last images of the slide show is selected
				var isLastThumbnailImage = (images.length - imageIndex) <= 1;

				// Load some more images when gallery is at the end
				if (isLastThumbnailImage && infinitesSrollGallery.ajaxReady && moreImagesToLoad) {
					infinitesSrollGallery.numberOfScroll ++;
					var limit = $('#tx-infinitescrollgallery-limit').val();
					var offset = parseInt(limit * infinitesSrollGallery.numberOfScroll);

					// load new batch of images
					$('#tx-infinitescrollgallery-offset').val(offset);
					$('#tx-infinitescrollgallery-form').submit();
				}
			}
		}
	}

	// Launch the gallery
	$(".yoxview").yoxview(slideShowConfiguration);

	///////////// FORM /////////////

	/**
	 * Attach event to the drop down menu
	 */
	$('#tx-infinitescrollgallery-category').change(function(event) {

		// unbind registered events on images (prevent a double select image bug)
		$.yoxview.unload();

		// Reset variable
		infinitesSrollGallery.numberOfScroll = 0;
		$('#tx-infinitescrollgallery-offset').val(0);
		$('#tx-infinitescrollgallery-form').submit();

		// Empty image stack before loading
		$('.yoxview ul').html('');
		$('#tx-infinitescrollgallery-recordnumber').hide();
	});


	/**
	 * Attach event to the search field
	 */
	$('#tx-infinitescrollgallery-searchTerm').keydown(function(event) {

		// True when key 'enter' hit
		if (event.keyCode == 13) {

			// unbind registered events on images (prevent a double select image bug)
			$.yoxview.unload();

			// Reset variable
			infinitesSrollGallery.numberOfScroll = 0;
			$('#tx-infinitescrollgallery-offset').val(0);
			$('#tx-infinitescrollgallery-form').submit();

			// Empty image stack before loading
			$('.yoxview ul').html('');
			$('#tx-infinitescrollgallery-recordnumber').hide();

			event.preventDefault();
		}
	});

	// Function scroll to load new images when scrolling down
	$(window).scroll(function() {
		// "enableMoreLoading" is a setting coming from the BE bloking / enabling dynamic loading of thumbnail
		if (infinitesSrollGallery.enableMoreLoading && $(window).scrollTop() >= $(document).height() - $(window).height() - 10) {

			// Computes if there are more images to display and an ajax request can be sent against the server
			var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
			var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());
			var moreImagesToLoad =  numberOfVisibleImages < totalImages;

			if (infinitesSrollGallery.ajaxReady && moreImagesToLoad) {

				// unbind registered events on images (prevent a double select image bug)
				$.yoxview.unload();

				infinitesSrollGallery.numberOfScroll ++;
				$('.tx-infinitescrollgallery-next a').hide();
				var limit = $('#tx-infinitescrollgallery-limit').val();
				var offset = parseInt(limit * infinitesSrollGallery.numberOfScroll);

				// load new set of images
				$('#tx-infinitescrollgallery-offset').val(offset);

				// Fix potential bug if placeholder is not supported
				if ($('#tx-infinitescrollgallery-searchTerm').attr('value') == $('#tx-infinitescrollgallery-searchTerm').attr('placeholder')) {
					$('#tx-infinitescrollgallery-searchTerm').attr('value', '');
				}
				$('#tx-infinitescrollgallery-form').submit()
			}
		}
	});

	// Initialize form configuration Object
	var formConfiguration = {
		url : $('#tx-infinitescrollgallery-url').val(), // override default action of the form.
		data : {
			type : 1363971892 // custom type for infinite scroll gallery.
		},
		beforeSubmit : function(arr, form, options) {
			doSubmit = false;
			if (infinitesSrollGallery.ajaxReady) {

				// lock the ajax process
				infinitesSrollGallery.ajaxReady = false;
				doSubmit = true;

				// UI update
				$('#tx-infinitescrollgallery-searchTerm, #tx-infinitescrollgallery-category').attr('disabled', 'disabled');
				$(".tx-infinitescrollgallery-loading").show();

			}
			return doSubmit;
		},
		success : function(result) {
			if (result) {

				// Add new batch of images into its right place
				$(".yoxview ul").append(result);
				slideShowConfiguration.images = infinitesSrollGallery.imageStack;

				// reset the lock
				infinitesSrollGallery.ajaxReady = true;
				$('#tx-infinitescrollgallery-searchTerm, #tx-infinitescrollgallery-category').removeAttr('disabled');
				$(".yoxview").yoxview(slideShowConfiguration);

				// Page Browser UI
				var numberOfVisibleImages = $('.yoxview ul li').length;
				$("#tx-infinitescrollgallery-numberOfVisibleImages").html(numberOfVisibleImages);

				var matches = result.match(/###totalImages:([0-9]+)###/);
				var totalImages = matches[1];
				$("#tx-infinitescrollgallery-totalImages").html(totalImages);

			}
			$(".tx-infinitescrollgallery-loading").hide();
			$('#tx-infinitescrollgallery-recordnumber').show();
		}
	};

	// Bind event to the form
	$('#tx-infinitescrollgallery-form').ajaxForm(formConfiguration);
});
