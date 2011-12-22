/*
* Common Listner
*/
///////////// RUNTIME VARIABLES /////////////
var infinitesSrollGallery = {
	ajaxReady : true,
	numberOfScroll : 0
}

$(document).ready(function() {

	///////////// GALLERY /////////////
	// Set plugin configuration
	var slideShowConfiguration = {
		lang : $('#tx-infinitescrollgallery-language').val() ? $('#tx-infinitescrollgallery-language').val() : 'en',
		onEnd : function() {
			$.yoxview.close();
		},
		images: infinitesSrollGallery.stockImages,
		onSelect: function(imageIndex, image) {

			var images = $('.yoxview ul li');
			
			// Computes if there are more images to display and an ajax request can be sent against the server
			var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
			var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());
			var moreImagesToLoad =  numberOfVisibleImages < totalImages;

			// Determines if the last images of the slideshow is selected
			var isLastThumbnailImage = (images.length - imageIndex) <= 1;
			
			// Load some more images when gallery is at the end
			if (isLastThumbnailImage && infinitesSrollGallery.ajaxReady && moreImagesToLoad) {
				infinitesSrollGallery.numberOfScroll ++;
				var limit = $('#tx-infinitescrollgallery-limit').val();
				offset = parseInt(limit * infinitesSrollGallery.numberOfScroll);
	
				// load new batch of images
				$('#tx-infinitescrollgallery-offset').val(offset);
				$('#tx-infinitescrollgallery-form').submit();
			}
		} 
	}

	// Launch the gallery
	$(".yoxview").yoxview(slideShowConfiguration);

	///////////// FORM /////////////
	
	// Attach event to the drop down menu
	$('#tx-infinitescrollgallery-tag').change(function(event) {

		// unbind registered events on images (prevent a double select image bug)
 		$.yoxview.unload()
 			
		// Reset variable
		infinitesSrollGallery.numberOfScroll = 0;
		$('#tx-infinitescrollgallery-offset').val(0);
		$('#tx-infinitescrollgallery-form').submit();
		
		// Empty image stack before loading 
		$('.yoxview ul').html('');
		$('#tx-infinitescrollgallery-recordnumber').hide();
	});
	
	// Attach event to the search field
	$('#tx-infinitescrollgallery-search').keydown(function(event) {

		// True when key 'enter' hit
		if (event.keyCode == 13) {
			
			// unbind registered events on images (prevent a double select image bug)
 			$.yoxview.unload()
 				
			// Reset variable
			infinitesSrollGallery.numberOfScroll = 0;
			$('#tx-infinitescrollgallery-offset').val(0);
			
			
			// Empty image stack before loading 
			$('.yoxview ul').html('');
			$('#tx-infinitescrollgallery-recordnumber').hide();
		}
	});
	
	// Function scroll to load new images when scrolling down
	$(window).scroll(function() {
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 10) {
			
			// Computes if there are more images to display and an ajax request can be sent against the server
			var numberOfVisibleImages = parseInt($("#tx-infinitescrollgallery-numberOfVisibleImages").html());
			var totalImages = parseInt($("#tx-infinitescrollgallery-totalImages").html());
			var moreImagesToLoad =  numberOfVisibleImages < totalImages;
			
			if (infinitesSrollGallery.ajaxReady && moreImagesToLoad) {

				// unbind registered events on images (prevent a double select image bug)
 				$.yoxview.unload()
 			
				infinitesSrollGallery.numberOfScroll ++;
				$('.tx-infinitescrollgallery-next a').hide();
				var limit = $('#tx-infinitescrollgallery-limit').val();
				offset = parseInt(limit * infinitesSrollGallery.numberOfScroll);

				// load new set of images
				$('#tx-infinitescrollgallery-offset').val(offset);
				
				// Fix potential bug if placeholder is not supported
				if ($('#tx-infinitescrollgallery-search').attr('value') == $('#tx-infinitescrollgallery-search').attr('placeholder')) {
					$('#tx-infinitescrollgallery-search').attr('value', '');
				}
				$('#tx-infinitescrollgallery-form').submit()
			}
		}
	});
	
	// Initialize form configuration Object
	var formConfiguration = {
		url : 'index.php', // override default value
		data : {
			type : 83790 // default parameter
		},
		beforeSubmit : function(arr, form, options) {
			doSubmit = false;
			if (infinitesSrollGallery.ajaxReady) {
				
				// lock the ajax process
				infinitesSrollGallery.ajaxReady = false;
				doSubmit = true;
				
				// UI update
				$('#tx-infinitescrollgallery-search, #tx-infinitescrollgallery-tag').attr('disabled', 'disabled');
				$(".tx-infinitescrollgallery-loading").show();

			}
			return doSubmit;
		},
		success : function(result) {
			if (result) {
				
				// Add new batch of images into its right place
				$(".yoxview ul").append(result);
				slideShowConfiguration.images = infinitesSrollGallery.stockImages; 

				// reset the lock
				infinitesSrollGallery.ajaxReady = true;
				$('#tx-infinitescrollgallery-search, #tx-infinitescrollgallery-tag').attr('disabled', '');
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
