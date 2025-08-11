/**
 * Natural Gallery TYPO3 Initialization Script
 * Compatible with Natural Gallery JS v10.x
 */
(function() {
    'use strict';

    // Import the gallery classes from the global scope
    const { Natural, Masonry, Square } = window;

    // Global gallery instances
    window.naturalGalleryInstances = window.naturalGalleryInstances || {};

    /**
     * Initialize a single gallery
     */
    function initializeGallery(config) {
        const element = document.getElementById(config.id);
        if (!element) {
            console.error('Gallery element not found:', config.id);
            return;
        }

        // Convert old config format to new format
        const galleryOptions = convertOptions(config.options);
        
        // Convert images data to new format
        const galleryData = convertImages(config.images);

        // Determine gallery type based on options
        let GalleryClass;
        if (config.options.format === 'square' && config.options.imagesPerRow) {
            GalleryClass = Square;
            galleryOptions.itemsPerRow = config.options.imagesPerRow;
        } else if (config.options.format === 'square' && config.options.rowHeight) {
            GalleryClass = Masonry;
            galleryOptions.columnWidth = config.options.rowHeight;
        } else {
            // Default to Natural (justified) layout
            GalleryClass = Natural;
            galleryOptions.rowHeight = config.options.rowHeight || 350;
        }

        try {
            // Create the gallery instance
            const gallery = new GalleryClass(element, galleryOptions);
            
            // Store instance for later access
            window.naturalGalleryInstances[config.id] = gallery;
            
            // Add images to gallery
            gallery.addItems(galleryData);
            
            console.log('Initialized gallery:', config.id);
            
            return gallery;
        } catch (error) {
            console.error('Failed to initialize gallery:', config.id, error);
        }
    }

    /**
     * Convert old options to new API format
     */
    function convertOptions(oldOptions) {
        const newOptions = {
            gap: oldOptions.margin || 3,
            showLabels: oldOptions.showLabels === 'hover' ? 'hover' : (oldOptions.showLabels === 'true' ? 'always' : false),
            lightbox: oldOptions.lightbox === true || oldOptions.lightbox === 'true'
        };

        // Add ratio limits if needed
        if (oldOptions.format === 'natural') {
            newOptions.ratioLimit = { min: 0.5, max: 2.0 };
        }

        return newOptions;
    }

    /**
     * Convert old image format to new format
     */
    function convertImages(images) {
        if (!Array.isArray(images)) {
            return [];
        }

        return images.map(function(img) {
            return {
                id: img.id,
                thumbnailSrc: img.thumbnail,
                enlargedSrc: img.enlarged,
                enlargedWidth: parseInt(img.eWidth) || 800,
                enlargedHeight: parseInt(img.eHeight) || 600,
                title: img.title || '',
                link: img.link || null,
                linkTarget: img.linkTarget || null
            };
        });
    }

    /**
     * Initialize all galleries from global array
     */
    function initializeAllGalleries() {
        if (typeof window.naturalGalleries !== 'undefined' && Array.isArray(window.naturalGalleries)) {
            window.naturalGalleries.forEach(function(config) {
                initializeGallery(config);
            });
        }
    }

    // Initialize galleries when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAllGalleries);
    } else {
        // DOM already loaded
        initializeAllGalleries();
    }

    // Re-initialize if more galleries are added later
    if (typeof window.naturalGalleries !== 'undefined') {
        const originalPush = window.naturalGalleries.push;
        window.naturalGalleries.push = function(...configs) {
            originalPush.apply(this, configs);
            configs.forEach(initializeGallery);
        };
    }

})();