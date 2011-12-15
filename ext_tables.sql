#
# Table structure for table 'tx_infinitescrollgallery_domain_model_gallery'
#
CREATE TABLE tx_infinitescrollgallery_domain_model_gallery (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,


	title varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY language (l10n_parent,sys_language_uid)
);

#
# Additional fields for table 'tt_content'
#
CREATE TABLE tt_content (
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
	
);
