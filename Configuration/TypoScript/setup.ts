#################################
# plugin.tx_infinitescrollgallery
#################################

plugin.tx_infinitescrollgallery {
	view {
		templateRootPath = {$plugin.tx_infinitescrollgallery.view.templateRootPath}
		partialRootPath = {$plugin.tx_infinitescrollgallery.view.partialRootPath}
		layoutRootPath = {$plugin.tx_infinitescrollgallery.view.layoutRootPath}
	}
	persistence {
		storagePid = {$plugin.tx_infinitescrollgallery.persistence.storagePid}
	}

	settings {

		asset {

			photoSwipeCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/photoswipe.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			photoSwipeThemeCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/default-skin/default-skin.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			infiniteScrollGalleryCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/natural-gallery.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			photoSwipeJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/photoswipe.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
			photoSwipeThemeJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/photoswipe-ui-default.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}

			organizer {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/natural-gallery-organizer.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}


			infiniteScrollGalleryJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/natural-gallery.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
		}

		loadAssetWithVhsIfAvailable = 1
	}
}
