plugin.tx_infinitescrollgallery {
	view {
		# cat=plugin.tx_infinitescrollgallery/file; type=string; label=Path to template root (FE)
		templateRootPath = EXT:infinite_scroll_gallery/Resources/Private/Templates/
		# cat=plugin.tx_infinitescrollgallery/file; type=string; label=Path to template partials (FE)
		partialRootPath = EXT:infinite_scroll_gallery/Resources/Private/Partials/
		# cat=plugin.tx_infinitescrollgallery/file; type=string; label=Path to template layouts (FE)
		layoutRootPath = EXT:infinite_scroll_gallery/Resources/Private/Layouts/
	}
	persistence {
		# cat=plugin.tx_infinitescrollgallery//a; type=int+; label=Default storage PID
		storagePid =
	}
}