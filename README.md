# Meta Tag Extraction
[![Build Status](https://travis-ci.org/yourusername/metatag-extraction.svg?branch=main)](https://travis-ci.org/yourusername/metatag-extraction)
[![Coverage Status](https://coveralls.io/repos/github/yourusername/metatag-extraction/badge.svg?branch=main)](https://coveralls.io/github/yourusername/metatag-extraction?branch=main)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

### About
Meta Tag Extraction is a light-weight library for extracting meta tags from HTML content.

It allows you to:
- get all **meta tags from URL or HTML content** (including `title`, `charset`, and `html[lang]` attribute),
- set custom cache provider for caching the results of web scraping (using PSR-16 `Psr\SimpleCache\CacheInterface`),
- set custom HTTP client for web scraping (using PSR-18 `Psr\Http\Client\ClientInterface`),

This library can be considered as extended alternative for [get_meta_tags()](https://www.php.net/manual/en/function.get-meta-tags.php) function.

### Requirements
- PHP 8.1 or higher
- PHP DOM extension

### Installation
```bash
composer require martinille/meta-tag-extraction
```

### Usage
```php
use Martinille\MetaTagExtraction\MetaTagExtractor;

$url = 'https://example.com';
$metaTagExtraction = new MetaTagExtraction();

$metaTags = $metaTagExtraction->extractFromUrl($url);
```

### Examples
Extracting meta tags from HTML content: [docs/MetaTagExtraction_html_example.php](docs/MetaTagExtraction_html_example.php)

Extracting meta tags from a URL: [docs/MetaTagExtraction_url_example.php](docs/MetaTagExtraction_url_example.php)

More examples: [docs/index.md](docs/index.md)

### Unit Testing

Unit tests:
```bash
composer test
```

Unit tests with coverage:
```bash
composer test:coverage
```
