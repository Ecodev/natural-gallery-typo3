# Natural Gallery JS v10.0.1 Upgrade - Breaking Changes

This update upgrades the Natural Gallery JavaScript library from v2.5.0 to v10.0.1, bringing significant improvements and new features. **This is a major version upgrade with breaking changes**.

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

## Breaking Changes

This is a major version upgrade that requires changes to your TYPO3 configuration and templates.

### Required Changes

1. **Update your templates**: The gallery initialization has changed significantly
2. **Review custom JavaScript**: Any custom code interacting with galleries needs updating
3. **Update configuration**: Some options have changed names or behavior

### Migration Notes

#### For Developers

**Old (v2.5.0):**
```javascript
// Old global constructor approach
var gallery = new NaturalGallery(config);
```

**New (v10.0.1):**
```javascript
// New ES module classes - requires manual integration
import { Natural, Masonry, Square } from '@ecodev/natural-gallery-js';
var gallery = new Natural(element, options);
```

#### Image Loading Changes

In v2.5.0, TYPO3 had to generate all thumbnails upfront, which could freeze the page. In v10.0.1:

- Thumbnails are generated on-demand
- Only visible images are loaded initially
- Infinite scroll loads images as needed
- Server load is distributed over time

#### API Changes

- Option `margin` is now `gap`
- Gallery initialization pattern has changed
- Image data format may need updating
- Some configuration options have been renamed or removed

## Testing the Upgrade

After upgrading, verify that:

1. Galleries load and display correctly
2. Lightbox (PhotoSwipe) opens and works
3. Infinite scroll loads additional images
4. Touch/swipe gestures work on mobile
5. Keyboard navigation functions properly

## Configuration Migration

You will need to:

1. **Update TypoScript**: Review and update gallery configurations
2. **Update Templates**: Modify Fluid templates to work with new API
3. **Update JavaScript**: Any custom gallery code needs updating
4. **Test Thoroughly**: This is a breaking change requiring careful testing

## Support

This is a major version upgrade. Please thoroughly test your galleries after upgrading and:

1. Check browser console for JavaScript errors
2. Verify all required files are loaded
3. Ensure TYPO3 cache is cleared
4. Test with different gallery configurations

For bugs or questions, please report them in the project's GitHub repository.