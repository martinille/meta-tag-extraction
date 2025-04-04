<?php
/**
 * This script demonstrates how to use the WebScraper class to fetch HTML content from a URL with caching.
 * @package MetaTagExtraction
 */

use MartinIlle\MetaTagExtraction\WebScraper;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

require_once 'vendor/autoload.php';

// Set up the cache adapter
// You can change the cache directory as needed
// You can also use other cache adapters like RedisAdapter, MemcachedAdapter, etc.
$cache = new Psr16Cache(
    new FilesystemAdapter(
        namespace: 'WebScraper', defaultLifetime: 10,
    )
);

// Create a new WebScraper instance
$scraper = new WebScraper();
$scraper->setCache($cache);

try {
    // Fetch HTML content from a URL
    $response = $scraper->fetch('https://example.com');
    $html = $response->getBody()->getContents();

    // Prepare the HTML content for display
    $html = preg_replace('/\s+/', ' ', $html);
    $html = substr($html, 0, 100).'...';
    echo 'HTML content (with cache): '.$html.PHP_EOL;
} catch (ClientExceptionInterface|InvalidArgumentException $e) {
    echo 'Error: '.$e->getMessage();
}

echo PHP_EOL;

