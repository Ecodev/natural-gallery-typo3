===============
Natural Gallery
===============

Display images as you scroll. Images are displayed within a slideshow when enlarged.
Under the hood it uses the Media API and relies on categories to filter images on the FE.

.. image:: https://raw.github.com/Ecodev/natural_gallery/master/Documentation/Introduction-01.png

Natural Gallery JS v10.0.1 Upgrade
===================================

This extension has been upgraded to use Natural Gallery JS v10.0.1, bringing significant performance improvements and new features while maintaining full backward compatibility with existing TYPO3 installations.

**Key Improvements:**

- **Better Performance**: Autonomous image loading and optimized infinite scroll
- **Reduced Server Load**: Images are generated on-demand instead of all upfront
- **Modern Architecture**: ES modules and PhotoSwipe v5.x integration
- **Smaller Bundle**: Reduced JavaScript bundle size
- **Enhanced Mobile**: Better touch and swipe gesture support

**Backward Compatibility:**

All existing templates, TypoScript, and FlexForm configurations continue to work without any changes. The upgrade includes an automatic compatibility layer.

For detailed upgrade information, see ``Documentation/UPGRADE-v10.md``.


Project info and releases
=========================

The home page of the project is on https://github.com/Ecodev/natural_gallery.git

Stable version:
http://typo3.org/extensions/repository/view/natural_gallery

Development version:
https://github.com/Ecodev/natural_gallery.git

::

	git clone https://github.com/Ecodev/natural_gallery.git

Users manual
============

To install a gallery on a page, click on the page where the gallery should be displayed and create a new content element.

.. image:: https://raw.github.com/Ecodev/natural_gallery/master/Documentation/UserManual-01.png

Choose the plugin "Natural Gallery"

.. image:: https://raw.github.com/Ecodev/natural_gallery/master/Documentation/UserManual-02.png

Give a Header if necessary and choose the "Plugin" tab.

.. image:: https://raw.github.com/Ecodev/natural_gallery/master/Documentation/UserManual-03.png

Choose "Natural Gallery" plugin

.. image:: https://raw.github.com/Ecodev/natural_gallery/master/Documentation/UserManual-04.png

Once the plugin "Natural Gallery" is selected, configuration is self explanatory.

Configuration
=============

.. .....................................................................................
.. container:: table-row

Key
	view.templateRootPath

Datatype
	string

Description
	Path to template root

Default
	EXT:natural_gallery/Resources/Private/Templates/

.. .....................................................................................
.. container:: table-row

Key
	view.partialRootPath

Datatype
	string

Description
	Path to template partials

Default
	EXT:natural_gallery/Resources/Private/Partials/


.. .....................................................................................
.. container:: table-row

Key
	view.layoutRootPath

Datatype
	string

Description
	Path to template layouts

Default
	EXT:natural_gallery/Resources/Private/Layouts/

.. .....................................................................................
.. container:: table-row

Key
	persistence.storagePid

Datatype
	int

Description
	Path to template layouts

Default
	EXT:natural_gallery/Resources/Private/Layouts/