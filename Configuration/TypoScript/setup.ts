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

			PhotoSwipeCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/photoswipe.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			PhotoSwipeThemeCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/default-skin/default-skin.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			InfiniteScrollGalleryCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/InfiniteScrollGallery.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			LodashJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/lodash.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}

			PhotoSwipeJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/photoswipe.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
			PhotoSwipeThemeJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/photoswipe-ui-default.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}

			Organizer {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/InfiniteScrollGalleryOrganizer.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}


			InfiniteScrollGalleryJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/InfiniteScrollGallery.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
		}

		loadAssetWithVhsIfAvailable = 1
	}
}
