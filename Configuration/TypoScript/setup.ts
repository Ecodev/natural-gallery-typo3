#################################
# plugin.tx_naturalgallery
#################################

plugin.tx_naturalgallery {
	view {
		templateRootPath = {$plugin.tx_naturalgallery.view.templateRootPath}
		partialRootPath = {$plugin.tx_naturalgallery.view.partialRootPath}
		layoutRootPath = {$plugin.tx_naturalgallery.view.layoutRootPath}
	}
	persistence {
		storagePid = {$plugin.tx_naturalgallery.persistence.storagePid}
	}

	settings {

		asset {

			photoSwipeCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/photoswipe.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			photoSwipeThemeCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/default-skin/default-skin.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			naturalGalleryCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/natural-gallery.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}

			photoSwipeJs {
				path = EXT:natural_gallery/Resources/Public/JavaScript/photoswipe.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
			photoSwipeThemeJs {
				path = EXT:natural_gallery/Resources/Public/JavaScript/photoswipe-ui-default.min.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}

			organizer {
				path = EXT:natural_gallery/Resources/Public/JavaScript/natural-gallery-organizer.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}


			naturalGalleryJs {
				path = EXT:natural_gallery/Resources/Public/JavaScript/natural-gallery.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
		}

		loadAssetWithVhsIfAvailable = 1
	}
}
