# Natural Gallery TYPO3 Extension

Natural Gallery is a TYPO3 CMS extension that provides a lazy-loading, infinite scroll photo gallery with natural layout and slideshow functionality. The extension is written in PHP using TYPO3's Extbase framework with Fluid templates, and integrates the natural-gallery-js JavaScript library for frontend functionality.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Initial Setup and Dependencies
- Install Node.js dependencies:
  - `npm install` -- takes under 1 second. NEVER CANCEL. Set timeout to 30+ seconds.
- Install PHP dependencies:
  - `composer config --no-plugins allow-plugins true` -- Allow all composer plugins
  - `COMPOSER_NO_INTERACTION=1 composer install --ignore-platform-reqs` -- takes 15-20 seconds. NEVER CANCEL. Set timeout to 60+ seconds.
- Validate PHP syntax:
  - `find Classes -name "*.php" -exec php -l {} \;` -- takes under 1 second. NEVER CANCEL. Set timeout to 30+ seconds.

### Key Project Structure
```
Classes/                    # PHP source code (MVC architecture)
├── Controller/            # Extbase controllers
├── Domain/               # Domain models and repositories
├── Persistence/          # Custom persistence logic
├── Utility/              # Helper utilities
├── Backend/              # TYPO3 backend integration
└── ViewHelpers/          # Fluid template helpers

Resources/                 # Frontend assets and templates
├── Private/              # Fluid templates, partials, layouts
└── Public/               # CSS, JavaScript, images

Configuration/            # TYPO3 configuration
├── FlexForm/            # FlexForm definitions
├── Services.yaml        # Dependency injection
└── TypoScript/          # TypoScript configuration

ext_*.php                # TYPO3 extension configuration files
composer.json            # PHP dependencies
package.json             # JavaScript dependencies (minimal)
```

### Build and Validation Commands
- **NO BUILD PROCESS REQUIRED** - This extension uses pre-built assets
- PHP syntax validation: `find Classes -name "*.php" -exec php -l {} \;` (< 1 second)
- JavaScript assets are pre-compiled - no build step needed
- Extension integrates natural-gallery-js library from node_modules

### Development Workflow
- This is a TYPO3 extension, not a standalone application
- Cannot be "run" independently - requires full TYPO3 installation
- Development involves editing PHP classes, Fluid templates, and configuration
- Frontend functionality depends on natural-gallery-js library
- Extension provides plugin for TYPO3 content elements

## Validation

### Code Quality Checks
- PHP syntax: `find Classes -name "*.php" -exec php -l {} \;`
- All PHP files must pass syntax validation
- No automated tests available - manual testing required in TYPO3 context
- Extension follows TYPO3 coding standards and Extbase conventions

### Manual Testing Scenarios
Since this is a TYPO3extension, full testing requires a TYPO3 installation. However, you can validate:

1. **PHP Code Integrity**:
   - All PHP files pass syntax validation
   - Extension configuration files are valid
   - Dependencies install correctly

2. **Frontend Assets**:
   - JavaScript assets are present in Resources/Public/JavaScript/
   - CSS assets are present in Resources/Public/StyleSheets/
   - natural-gallery-js dependency is installed in node_modules/

3. **Configuration Validation**:
   - FlexForm XML is well-formed
   - TypoScript configuration is syntactically correct
   - Services.yaml follows TYPO3 conventions

### Common Validation Steps
- Always run `find Classes -name "*.php" -exec php -l {} \;` after making PHP changes
- Verify dependencies with `npm list` and `composer show`
- Check extension configuration files for syntax errors

## Common Tasks

### Working with PHP Code
- Extension follows TYPO3 Extbase MVC pattern
- Controllers in Classes/Controller/ handle requests
- Domain models in Classes/Domain/Model/ represent data
- Repositories in Classes/Domain/Repository/ handle data access
- ViewHelpers in Classes/ViewHelpers/ provide Fluid template functionality

### Frontend Assets
- JavaScript: Resources/Public/JavaScript/natural-gallery.full.js (pre-built)
- CSS: Resources/Public/StyleSheets/natural-gallery.full.css (pre-built)
- Icons and images: Resources/Public/Images/
- Assets are served by TYPO3, not built during development

### Templates and Configuration
- Fluid templates: Resources/Private/Templates/
- Template partials: Resources/Private/Partials/
- Template layouts: Resources/Private/Layouts/
- FlexForm configuration: Configuration/FlexForm/NaturalGallery.xml
- TypoScript: Configuration/TypoScript/

### Dependency Management
- PHP dependencies managed via Composer (vendor/ directory)
- JavaScript dependencies minimal - only natural-gallery-js for frontend
- Extension expects to be installed in TYPO3 extension directory

## Timing Expectations

- npm install: < 1 second
- composer install: 15-20 seconds (with GitHub rate limiting fallbacks)
- PHP syntax validation: < 1 second for all files
- No build process - all assets are pre-compiled

## Important Notes

- This is a TYPO3 CMS extension, not a standalone web application
- Cannot be run independently - requires TYPO3 installation
- Frontend functionality depends on natural-gallery-js JavaScript library
- Extension provides TYPO3 plugin for content elements
- Development focuses on PHP backend logic and Fluid templates
- No automated test suite - validation is primarily syntax checking
- Extension integrates with TYPO3's Media API and category system

## Troubleshooting

### Common Issues
- **Composer plugin errors**: Run `composer config --no-plugins allow-plugins true`
- **GitHub rate limiting**: Composer will fallback to source downloads automatically
- **PHP syntax errors**: Use `php -l filename.php` to check individual files
- **Missing dependencies**: Ensure both `npm install` and `composer install` complete successfully

### Development Tips
- Always check PHP syntax after making changes to Classes/
- Extension configuration in ext_*.php files affects TYPO3 registration
- FlexForm changes require TYPO3 cache clearing
- ViewHelpers must be properly namespaced for Fluid templates
- Follow TYPO3 coding standards and Extbase conventions