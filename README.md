# Meta Tag Extraction
[![Tests](https://github.com/martinille/meta-tag-extraction/actions/workflows/tests.yml/badge.svg)](https://github.com/martinille/meta-tag-extraction/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/martinille/meta-tag-extraction/badge.svg?branch=master)](https://coveralls.io/github/martinille/meta-tag-extraction?branch=master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

*A light-weight library for extracting meta tags from URL or HTML content.*

It allows you to:
- get all **meta tags from URL or HTML content** (including `title`, `charset`, and `html[lang]` attribute),
- set custom cache provider for caching the results of web scraping (using PSR-16 `Psr\SimpleCache\CacheInterface`),
- set custom HTTP client for web scraping (using PSR-18 `Psr\Http\Client\ClientInterface`),

This library can be considered as an extended alternative for [get_meta_tags()](https://www.php.net/manual/en/function.get-meta-tags.php) function.

### Requirements
- PHP 8.1 or higher
- PHP DOM extension

### Installation
```bash
composer require martinille/meta-tag-extraction
```

### Usage
```php
use MartinIlle\MetaTagExtraction\MetaTagExtraction;

$url = 'https://example.com';
$metaTagExtraction = new MetaTagExtraction();

$metaTags = $metaTagExtraction->extractFromUrl($url);

foreach ($metaTags as $tag) {
	echo $tag->getName() . ': ' . $tag->getValue() . PHP_EOL;
}
/* output:
charset: utf-8
content-type: text/html; charset=utf-8
viewport: width=device-width, initial-scale=1
title: Example Domain
*/
```

### Examples
- Extracting meta tags from HTML string: [docs/MetaTagExtraction_html_example.php](docs/MetaTagExtraction_html_example.php)
- Extracting meta tags from a URL: [docs/MetaTagExtraction_url_example.php](docs/MetaTagExtraction_url_example.php)

More examples: [docs/index.md](docs/index.md)

### Testing

Unit tests:
```bash
composer test
```

Unit tests with coverage:
```bash
composer test:coverage
```
