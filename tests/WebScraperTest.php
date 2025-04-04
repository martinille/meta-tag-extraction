<?php

declare(strict_types=1);

namespace MartinIlle\MetaTagExtraction\Tests;

use MartinIlle\MetaTagExtraction\WebScraper;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class WebScraperTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFetchesHtmlSuccessfully(): void
    {
        // Arrange
        $url = 'https://example.com';
        $htmlContent = '<html lang="en"><head><title>Example</title></head><body>Content</body></html>';

        // Mocking the HTTP client and request factory
        $httpClient = $this->createMock(ClientInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $request->method('withHeader')->willReturnSelf();
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory->method('createRequest')->willReturn($request);
        $response = $this->createMock(ResponseInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($htmlContent);
        $stream->method('__toString')->willReturn($htmlContent);
        $response->method('getBody')->willReturn($stream);
        $response->method('getStatusCode')->willReturn(200);
        $httpClient->method('sendRequest')->willReturn($response);
        $cache->method('get')->willReturn(null);

        // Setting up the WebScraper instance
        $scraper = new WebScraper();
        $scraper->setHttpClient($httpClient);
        $scraper->setRequestFactory($requestFactory);
        $scraper->setCache($cache);

        // Act
        $result = $scraper->fetch($url);

        // Assert
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($htmlContent, (string)$result->getBody());
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFetchesHtmlFromCache(): void
    {
        $url = 'https://example.com';
        $htmlContent = '<html><head><title>Example</title></head><body>Content</body></html>';

        $httpClient = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $cache = $this->createMock(CacheInterface::class);

        $cache->method('get')->willReturn($htmlContent);

        $scraper = new WebScraper();
        $scraper->setHttpClient($httpClient);
        $scraper->setRequestFactory($requestFactory);
        $scraper->setCache($cache);

        $result = $scraper->fetch($url);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals($htmlContent, (string)$result->getBody());
    }

    public function testThrowsExceptionForEmptyUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $scraper = new WebScraper();
        $scraper->fetch('');
    }

    public function testThrowsExceptionForInvalidUrl(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $scraper = new WebScraper();
        $scraper->fetch('invalid-url');
    }

    /**
     * @throws Exception
     */
    public function testThrowsExceptionForInvalidCacheTtl(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $scraper = new WebScraper();
        $scraper->setCache($this->createMock(CacheInterface::class), 0);
    }

    public function testThrowsExceptionForInvalidCacheImplementation(): void
    {
        $this->expectException(\TypeError::class);

        $scraper = new WebScraper();
        $scraper->setCache(new \stdClass());
    }

    /**
     * @throws \ReflectionException
     */
    public function testCacheKeyGeneration(): void
    {
        $url = 'https://example.com';
        $expectedKey = 'MartinIlle\MetaTagExtraction\WebScraper::getCacheKey_c984d06a';

        $reflection = new \ReflectionMethod(WebScraper::class, 'getCacheKey');

        $scraper = new WebScraper();
        $cacheKey = $reflection->invoke($scraper, $url);

        $this->assertEquals($expectedKey, $cacheKey);
    }
}
