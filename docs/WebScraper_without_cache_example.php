<?php
/**
 * This script demonstrates how to use the WebScraper class to fetch HTML content from a URL without using cache.
 * @package MetaTagExtraction
 */

use MartinIlle\MetaTagExtraction\WebScraper;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

require_once 'vendor/autoload.php';


// Create an instance of the WebScraper class
$scraper = new WebScraper();

try {
    // Get HTTP response without using cache
    $response = $scraper->fetch('https://example.com');

    // Get the HTML content from the response
    $html = $response->getBody()->getContents();

    // Prepare the HTML content for output
    $html = preg_replace('/\s+/', ' ', $html); // Normalize whitespace
    $html = substr($html, 0, 100).'...'; // Truncate to 100 characters
    echo 'HTML content (no cache, truncated): '.$html.PHP_EOL;
} catch (ClientExceptionInterface|InvalidArgumentException $e) {
    echo 'Error: '.$e->getMessage();
}


