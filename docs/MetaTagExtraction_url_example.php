<?php
/**
 * This example demonstrates how to use the MetaTagExtraction library to extract meta tags from a URL.
 * @package MetaTagExtraction
 */

use MetaTagExtraction\MetaTagExtraction;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

require_once 'vendor/autoload.php';

$url = 'https://example.com';
$metaTagExtraction = new MetaTagExtraction();

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


