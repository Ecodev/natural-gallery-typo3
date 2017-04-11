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
				path = EXT:natural_gallery/Resources/Public/StyleSheets/photoswipe/photoswipe.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			photoSwipeThemeCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/photoswipe/default-skin/default-skin.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			naturalGalleryCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/natural-gallery-js/natural-gallery.min.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			naturalGalleryThemeCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/natural-gallery-js/natural-theme.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = naturalGalleryCss
			}
			naturalGalleryJs {
				path = EXT:natural_gallery/Resources/Public/JavaScript/natural-gallery.min.js
				#path = EXT:natural_gallery/Resources/Public/natural-gallery-js/dist/natural-gallery.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
		}

		loadAssetWithVhsIfAvailable = 1
	}
}
