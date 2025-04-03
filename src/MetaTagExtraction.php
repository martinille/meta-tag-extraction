<?php

declare(strict_types=1);

namespace MetaTagExtraction;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;

class MetaTagExtraction
{
    public function __construct(
        public readonly WebScraper $webScraper = new WebScraper(),
    ) {
    }

    /**
     * Extracts meta tags from a given URL.
     * @param string $url
     * @return array<Tag>
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     */
    public function extractMetaTagsFromUrl(string $url): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid URL provided');
        }

        $response = $this->webScraper->fetch($url);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch the URL');
        }

        $html = (string)$response->getBody();

        $metaTags = (new HtmlParserService($html))->parseMetaTags();

        return $metaTags;
    }

    /**
     * Extracts meta tags from a given HTML string.
     * @param string $html
     * @return array<Tag>
     * @throws \InvalidArgumentException
     */
    public function extractMetaTagsFromHtml(string $html): array
    {
        if (empty($html)) {
            throw new \InvalidArgumentException('Empty HTML provided');
        }

        $metaTags = (new HtmlParserService($html))->parseMetaTags();

        return $metaTags;
    }
}
