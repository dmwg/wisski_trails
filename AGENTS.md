# WissKI Trails - Agent Instructions

## Project Overview

This is a Drupal 10 module that integrates with the WissKI suite. It provides an iframe block that displays a document-graph visualization on entity detail pages.

**Key Architecture:**
- Drupal block plugin that can be placed on entity pages
- Configuration system with a `base_url` parameter
- Iframe source is built from: `{base_url}/{entity_id}` where entity_id is a number (e.g., "8734")
- PSR-4 autoloading with namespace `Drupal\WisskiTrails\`

## Commands

### Code Quality (Run these before committing)

```bash
# Check coding style
composer codingstyle
# or: composer cs, composer style

# Auto-fix coding style violations
composer codingstyle-fix
# or: composer fix, composer csfix, composer stylefix

# Run PHPStan static analysis
composer phpstan
# or: composer stan

# Normalize composer.json
composer normalize
```

### Testing

```bash
# Run unit tests
composer test

# Run tests with coverage report (requires Xdebug or PCOV)
composer test-coverage
```

### All Quality Checks
Run these commands to ensure code quality before committing:
```bash
composer codingstyle-fix && composer codingstyle && composer phpstan && composer test
```

## Code Style & Conventions

- **Standard**: Drupal coding standards
- **PHP Version**: >= 8.2
- **Drupal Version**: ^10.3
- **Namespace**: `Drupal\wisski_trails\`
- **Source Directory**: `src/`
- **PHPStan Level**: 5

## Module Configuration

The module has one main configuration parameter:
- `base_url`: Base URL for the iframe visualization service

The iframe src is constructed as: `{base_url}/{entity_id}`

## Package Information

- **Package Name**: `dmwg/wisski_trails`
- **Type**: drupal-module
- **License**: GPL-3.0-or-later
- **Distribution**: Packagist
