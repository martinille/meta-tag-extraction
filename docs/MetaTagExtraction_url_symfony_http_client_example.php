<?php
/**
 * This example demonstrates how to use
 * the MetaTagExtraction library to extract
 * meta tags from a URL using Symfony's HttpClient.
 * @package MetaTagExtraction
 */

use MartinIlle\MetaTagExtraction\MetaTagExtraction;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Psr18Client;

require_once 'vendor/autoload.php';

$url = 'https://example.com';
$metaTagExtraction = new MetaTagExtraction();

// Set the HTTP client to Symfony's HttpClient
$httpClient = new Psr18Client(HttpClient::create());
$metaTagExtraction->webScraper->setHttpClient($httpClient);

/** @noinspection DuplicatedCode */
try {
    // Extract all meta tags from the given URL
    $metaTags = $metaTagExtraction->extractFromUrl($url);

    // Print the extracted meta tags
    foreach ($metaTags as $metaTag) {
        echo "TagName: ".$metaTag->getTagName()."\n";
        echo "Name: ".$metaTag->getName()."\n";
        echo "Value: ".$metaTag->getValue()."\n";
        echo "\n";
    }
} catch (ClientExceptionInterface|InvalidArgumentException $e) {
    echo "Error: ".$e->getMessage()."\n";
}


