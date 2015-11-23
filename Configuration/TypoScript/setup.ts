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

			vidiCss {
				path = EXT:infinite_scroll_gallery/Resources/Public/StyleSheets/InfiniteScrollGallery.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			vidiJs {
				path = EXT:infinite_scroll_gallery/Resources/Public/JavaScript/InfiniteScrollGallery.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
		}

		loadAssetWithVhsIfAvailable = 1
	}
}
