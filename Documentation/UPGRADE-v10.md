# Natural Gallery JS v10.0.1 Upgrade

This update upgrades the Natural Gallery JavaScript library from v2.5.0 to v10.0.1, bringing significant improvements and new features while maintaining full backward compatibility.

## What's New in v10.0.1

### Performance Improvements
- **Autonomous Image Loading**: Gallery now decides what images it needs and requests them on-demand
- **Lazy Loading**: Only thumbnails that are visible or about to be visible are loaded
- **Reduced Bundle Size**: Modern ES module build is smaller and more efficient
- **Optimized Infinite Scroll**: Better performance during scrolling

### Technical Improvements
- **ES Module Architecture**: Modern JavaScript module system
- **PhotoSwipe v5.x**: Updated lightbox with better performance and features
- **Better Touch Support**: Improved mobile and touch device experience
- **Enhanced Accessibility**: Better screen reader and keyboard navigation support

## Backward Compatibility

All existing TYPO3 templates and configurations continue to work without any changes. The upgrade includes a compatibility layer that:

- Converts old option formats to new API
- Handles image data format conversion
- Maintains the global `naturalGalleries` initialization pattern
- Preserves all layout types (Natural, Square, Masonry)

## Migration Notes

### For Developers

If you have custom JavaScript that interacts with galleries, note these changes:

**Old (v2.5.0):**
```javascript
// Global constructor
var gallery = new NaturalGallery(config);
```

**New (v10.0.1):**
```javascript
// ES module classes
import { Natural, Masonry, Square } from 'natural-gallery-js';
var gallery = new Natural(element, options);
```

The TYPO3 extension handles this automatically through the compatibility layer.

### Image Loading Changes

In v2.5.0, TYPO3 had to generate all thumbnails upfront, which could freeze the page. In v10.0.1:

- Thumbnails are generated on-demand
- Only visible images are loaded initially
- Infinite scroll loads images as needed
- Server load is distributed over time

## Testing the Upgrade

After upgrading, verify that:

1. Galleries load and display correctly
2. Lightbox (PhotoSwipe) opens and works
3. Infinite scroll loads additional images
4. Touch/swipe gestures work on mobile
5. Keyboard navigation functions properly

## Configuration Options

All existing TypoScript and FlexForm configurations remain compatible. The options are automatically converted:

- `margin` → `gap`
- `showLabels` logic preserved
- `lightbox` functionality maintained
- Layout selection (`format`) preserved

## Support

If you encounter any issues after the upgrade:

1. Check browser console for JavaScript errors
2. Verify all required files are loaded
3. Ensure TYPO3 cache is cleared
4. Test with different gallery configurations

For bugs or questions, please report them in the project's GitHub repository.