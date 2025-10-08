# WissKI Trails

[![CI](https://github.com/dmwg/wisski_trails/actions/workflows/ci.yml/badge.svg)](https://github.com/dmwg/wisski_trails/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/packagist/dependency-v/dmwg/wisski_trails/php?color=8892BF)](https://packagist.org/packages/dmwg/wisski_trails)
[![Drupal Version](https://img.shields.io/badge/drupal-^10.3%20%7C%7C%20^11-blue)](https://www.drupal.org)
[![License](https://img.shields.io/packagist/l/dmwg/wisski_trails)](LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/dmwg/wisski_trails)](https://packagist.org/packages/dmwg/wisski_trails)

A Drupal module that displays document-graph visualizations in an iframe on entity detail pages.
**This module depends on hitherto unreleased data-processing infrastructure and is not usable out of the box!**

## Description

WissKI Trails provides a configurable block that can be placed on entity detail pages to display interactive document-graph visualizations.
The module integrates with the WissKI ecosystem, allowing users to explore relationships between entities through visual representations.

The visualization is loaded via an iframe from a configurable external service, with the entity ID automatically passed to construct the appropriate visualization URL.

## Requirements

- `php>=8.3`
- Drupal 10.3 or higher (also compatible with Drupal 11)
- Drupal Views module (core)

## Installation

### Via Composer (recommended)

```bash
composer require dmwg/wisski_trails
```

Then enable the module:

```bash
drush en wisski_trails
```

Or via the Drupal admin interface at `admin/modules`.

### Manual Installation

1. Download the module from [GitHub](https://github.com/dmwg/wisski_trails)
2. Extract to your Drupal installation's `modules/contrib` directory
3. Enable the module via Drush or the admin interface

## Configuration

### Module Configuration

1. Navigate to **Configuration > Web Services > WissKI Trails** (`/admin/config/wisski_trails`)
2. Set the **Base URL** for your visualization service
3. Save the configuration

The iframe URL will be constructed as: `{base_url}/{entity_id}`

### Block Placement

1. Navigate to **Structure > Block layout** (`/admin/structure/block`)
2. Click **Place block** in your desired region
3. Find and place the **WissKI Trails** block
4. Configure visibility settings as needed (typically restricted to entity detail pages)
5. Save the block configuration

## Usage

Once configured and placed, the block will automatically:
1. Detect the current entity ID on entity detail pages
2. Construct the iframe URL using the configured base URL
3. Display the visualization iframe if an entity ID is found

## Development

### Prerequisites

- Composer installed
- PHP 8.3 or 8.4
- Git

### Getting Started

Clone the repository and install dependencies:

```bash
git clone https://github.com/dmwg/wisski_trails.git
cd wisski_trails
composer install
```

### Available Commands

#### Code Quality

Check coding style (Drupal standards):
```bash
composer codingstyle
# Aliases: composer cs, composer style
```

Auto-fix coding style violations:
```bash
composer codingstyle-fix
# Aliases: composer fix, composer csfix, composer stylefix
```

Run static analysis with PHPStan (level 5):
```bash
composer phpstan
# Alias: composer stan
```

Normalize composer.json:
```bash
composer normalize
```

#### Testing

Run unit tests:
```bash
composer test
```

Run tests with coverage report (requires Xdebug or PCOV):
```bash
composer test-coverage
```

#### Pre-commit Workflow

Before committing changes, run all quality checks:

```bash
composer codingstyle-fix \
  && composer codingstyle \
  && composer phpstan \
  && composer test
```

### Coding Standards

- **Standard**: Drupal coding standards
- **PHPStan Level**: 5
- **Namespace**: `Drupal\wisski_trails\`
- **PSR-4 Autoloading**: `src/` directory

## Maintainers

Current maintainer:

- Oliver Baumann - [DMWG, University of Bayreuth](https://www.dmwg.uni-bayreuth.de/en/index.html)

## Contributing

Issues and pull requests are welcome on [GitHub](https://github.com/dmwg/wisski_trails/issues).

## License

This project is licensed under GPL-3.0-or-later. See the [LICENSE](LICENSE) file for details.

## Support

- **Issues**: [GitHub Issues](https://github.com/dmwg/wisski_trails/issues)
- **Source**: [GitHub Repository](https://github.com/dmwg/wisski_trails)
