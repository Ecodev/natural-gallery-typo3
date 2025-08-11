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

			naturalGalleryCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/natural-gallery.full.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainCss
			}
			naturalGalleryThemeCss {
				path = EXT:natural_gallery/Resources/Public/StyleSheets/natural.css
				type = css

				# Optional key if loading assets through EXT:vhs.
				dependencies = naturalGalleryCss
			}
			naturalGalleryJs {
				path = EXT:natural_gallery/Resources/Public/JavaScript/natural-gallery.full.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = mainJs
			}
			naturalGalleryInitJs {
				path = EXT:natural_gallery/Resources/Public/JavaScript/natural-gallery-init.js
				type = js

				# Optional key if loading assets through EXT:vhs.
				dependencies = naturalGalleryJs
			}
		}

		loadAssetWithVhsIfAvailable = 1
	}
}
