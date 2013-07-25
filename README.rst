==========================
Infinite Scroll Gallery
==========================

Display images as you scroll. Images are displayed within a slideshow when enlarged.
Under the hood it uses the Media API and relies on categories to filter images on the FE.
Demo can bee seen on the `Bootstrap Package`_.

.. image:: https://raw.github.com/Ecodev/infinite_scroll_gallery/master/Documentation/Introduction-01.png

.. _Bootstrap Package: http://bootstrap.typo3cms.demo.typo3.org/examples-extended/gallery-slideshow/

Project info and releases
=============================

The home page of the project is at http://forge.typo3.org/projects/extension-infinite_scroll_gallery

Bug can be reported on `Forge`_ (preferred) or on `Github`_

Stable version:
http://typo3.org/extensions/repository/view/infinite_scroll_gallery

Development version:
https://github.com/Ecodev/infinite_scroll_gallery.git

::

	git clone https://github.com/Ecodev/infinite_scroll_gallery.git

.. _Forge: http://forge.typo3.org/projects/extension-infinite_scroll_gallery/issues/new
.. _Github: https://github.com/Ecodev/infinite_scroll_gallery/issues

Users manual
=================

To install a gallery on a page, click on the page where the gallery should be displayed and create a new content element.

.. image:: https://raw.github.com/Ecodev/infinite_scroll_gallery/master/Documentation/UserManual-01.png

Choose the plugin "Infinite Scroll Gallery"

.. image:: https://raw.github.com/Ecodev/infinite_scroll_gallery/master/Documentation/UserManual-02.png

Give a Header if necessary and choose the "Plugin" tab.

.. image:: https://raw.github.com/Ecodev/infinite_scroll_gallery/master/Documentation/UserManual-03.png

Choose "Infinite Scroll Gallery" plugin

.. image:: https://raw.github.com/Ecodev/infinite_scroll_gallery/master/Documentation/UserManual-04.png

Once the plugin "Infinite Scroll Gallery" is selected, configuration is self explanatory.

Configuration
===================

.. .....................................................................................
.. container:: table-row

Key
	view.templateRootPath

Datatype
	string

Description
	Path to template root

Default
	EXT:infinite_scroll_gallery/Resources/Private/Templates/

.. .....................................................................................
.. container:: table-row

Key
	view.partialRootPath

Datatype
	string

Description
	Path to template partials

Default
	EXT:infinite_scroll_gallery/Resources/Private/Partials/


.. .....................................................................................
.. container:: table-row

Key
	view.layoutRootPath

Datatype
	string

Description
	Path to template layouts

Default
	EXT:infinite_scroll_gallery/Resources/Private/Layouts/

.. .....................................................................................
.. container:: table-row

Key
	persistence.storagePid

Datatype
	int

Description
	Path to template layouts

Default
	EXT:infinite_scroll_gallery/Resources/Private/Layouts/