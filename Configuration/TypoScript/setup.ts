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

################################
# AJAX
################################

ajax1363971892 = PAGE
ajax1363971892 {
	# You don't need to change this typeNum
	typeNum = 1363971892
	10 = COA_INT
    10 {
        10 = USER_INT
        10 {
            userFunc = tx_extbase_core_bootstrap->run
            extensionName = InfiniteScrollGallery
            pluginName = Pi1

			# LIMIT CONTROLLER / ACTION
			switchableControllerActions {
				Gallery {
					1 = listAjax
				}
			}
        }
    }
	config {
		disableAllHeaderCode = 1
		additionalHeaders = Content-type:text/html
		xhtml_cleaning = 0
		admPanel = 0
	}
}
