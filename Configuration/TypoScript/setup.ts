###############################################################
# Extbase configuration
###############################################################
plugin.tx_infinitescrollgallery {
	view {
		templateRootPath = {$plugin.tx_infinitescrollgallery.view.templateRootPath}
		partialRootPath = {$plugin.tx_infinitescrollgallery.view.partialRootPath}
		layoutRootPath = {$plugin.tx_infinitescrollgallery.view.layoutRootPath}
	}
	persistence {
		storagePid = {$plugin.tx_infinitescrollgallery.persistence.storagePid}
	}
}
