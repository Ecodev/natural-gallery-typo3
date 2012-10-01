#
# Additional fields for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_infinitescrollgallery_enablemoreloading int(11) DEFAULT '0' NOT NULL,
        tx_infinitescrollgallery_enablevideo int(11) DEFAULT '0' NOT NULL,
        tx_infinitescrollgallery_showfilters int(11) DEFAULT '1' NOT NULL,

	tx_infinitescrollgallery_limit int(11) DEFAULT '0' NOT NULL,
	tx_infinitescrollgallery_thumbnailmaximumwidth int(11) DEFAULT '0' NOT NULL,
	tx_infinitescrollgallery_imagemaximumwidth int(11) DEFAULT '0' NOT NULL,

	# Insert tag uid to filter the selection by default
	tx_infinitescrollgallery_defaulttagfilter varchar(255) DEFAULT '' NOT NULL,

	# Insert tag category Id if any
	tx_infinitescrollgallery_tagcategory int(11) DEFAULT '0' NOT NULL,

	# Insert tag pid if any
	tx_infinitescrollgallery_tagpid int(11) DEFAULT '0' NOT NULL,


	# Insert maximum height for thumbnails
	tx_infinitescrollgallery_thumbnailmaximumheight  int(11) DEFAULT '0' NOT NULL,

	# Insert maximum height for images
	tx_infinitescrollgallery_imagemaximumheight int(11) DEFAULT '0' NOT NULL,

	tx_infinitescrollgallery_orderby varchar(255) DEFAULT '0' NOT NULL,

);
