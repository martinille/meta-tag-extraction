<?php
/**
 * This example demonstrates how to use the MetaTagExtraction library to extract meta tags from HTML content.
 * @package MetaTagExtraction
 */

use MetaTagExtraction\MetaTagExtraction;
use Psr\SimpleCache\InvalidArgumentException;

require_once 'vendor/autoload.php';

$html = <<<HTML
<html lang="en">
<head>
    <meta name="description" content="Example description">
    <meta property="og:title" content="Example Open Graph Title">
    <title>Lorem ipsum</title>
</head>
</html>
HTML;

$metaTagExtraction = new MetaTagExtraction();

try {
    // Extract all meta tags from the given URL
    $metaTags = $metaTagExtraction->extractMetaTagsFromHtml($html);

    // Print the extracted meta tags
    foreach ($metaTags as $metaTag) {
        echo "TagName: ".$metaTag->getTagName()."\n";
        echo "Name: ".$metaTag->getName()."\n";
        echo "Value: ".$metaTag->getValue()."\n";
        echo "\n";
    }
} catch (InvalidArgumentException $e) {
    echo "Error: ".$e->getMessage()."\n";
}


