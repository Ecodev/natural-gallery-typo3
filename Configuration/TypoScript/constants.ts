plugin.tx_naturalgallery {
	view {
		# cat=plugin.tx_naturalgallery/file; type=string; label=Path to template root (FE)
		templateRootPath = EXT:natural_gallery/Resources/Private/Templates/
		# cat=plugin.tx_naturalgallery/file; type=string; label=Path to template partials (FE)
		partialRootPath = EXT:natural_gallery/Resources/Private/Partials/
		# cat=plugin.tx_naturalgallery/file; type=string; label=Path to template layouts (FE)
		layoutRootPath = EXT:natural_gallery/Resources/Private/Layouts/
	}
	persistence {
		# cat=plugin.tx_naturalgallery//a; type=int+; label=Default storage PID
		storagePid =
	}
}