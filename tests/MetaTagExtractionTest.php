<?php

namespace MetaTagExtraction\Tests;

use MetaTagExtraction\MetaTagExtraction;
use MetaTagExtraction\Tag;
use MetaTagExtraction\WebScraper;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class MetaTagExtractionTest extends TestCase
{
    public function testMetaTagExtraction(): void
    {
        $html = '<html><head><meta property="og:title" content="Test Title"></head></html>';
        $metaTagExtraction = new MetaTagExtraction();
        $tags = $metaTagExtraction->extractMetaTagsFromHtml($html);

        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $this->assertEquals('meta', $tags[0]->getTagName());
        $this->assertEquals('Test Title', $tags[0]->getValue());
    }

    public function testExtractMetaTagsFromEmptyHtml(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $metaTagExtraction = new MetaTagExtraction();
        $metaTagExtraction->extractMetaTagsFromHtml('');
    }

    /**
     * @throws ClientExceptionInterface
     * @throws InvalidArgumentException
     */
    public function testExtractMetaTagsFromInvalidUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $metaTagExtraction = new MetaTagExtraction();
        $metaTagExtraction->extractMetaTagsFromUrl('invalid-url');
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     */
    public function testExtractMetaTagsFromValidUrl(): void
    {
        $webScraperMock = $this->createMock(WebScraper::class);
        $webScraperMock->method('fetch')->willReturn(new Response(200, [], '<html><head><meta property="og:title" content="Test Title"></head></html>'));

        $metaTagExtraction = new MetaTagExtraction($webScraperMock);
        $tags = $metaTagExtraction->extractMetaTagsFromUrl('https://example.com');

        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $this->assertEquals('meta', $tags[0]->getTagName());
        $this->assertEquals('Test Title', $tags[0]->getValue());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws ClientExceptionInterface
     */
    public function testExtractMetaTagsFromUrlWithCache(): void
    {
        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->method('get')->willReturn(null);
        $cacheMock->method('set')->willReturn(true);

        $webScraperMock = $this->createMock(WebScraper::class);
        $webScraperMock->method('fetch')->willReturn(new Response(200, [], '<html><head><meta property="og:title" content="Test Title"></head></html>'));

        $metaTagExtraction = new MetaTagExtraction($webScraperMock);
        $metaTagExtraction->webScraper->setCache($cacheMock, 60);
        $tags = $metaTagExtraction->extractMetaTagsFromUrl('https://example.com');

        $this->assertCount(1, $tags);
        $this->assertInstanceOf(Tag::class, $tags[0]);
        $this->assertEquals('meta', $tags[0]->getTagName());
        $this->assertEquals('Test Title', $tags[0]->getValue());
    }
}
