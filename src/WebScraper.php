<?php

declare(strict_types = 1);

namespace MetaTagExtraction;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class WebScraper
{
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
    private ?ClientInterface $httpClient = null;
    private ?RequestFactoryInterface $requestFactory = null;
    private ?CacheInterface $cache = null;
    private int $cacheTtl = 60;

    public function setCache(?CacheInterface $cache, int $ttl = 60): void
    {
        if ($this->cache !== null && !($this->cache instanceof CacheInterface)) {
            throw new \InvalidArgumentException('Cache must implement Psr\SimpleCache\CacheInterface');
        }
        if ($ttl <= 0) {
            throw new \InvalidArgumentException('Cache TTL must be greater than 0');
        }
        $this->cache = $cache;
        $this->cacheTtl = $ttl;
    }

    public function setRequestFactory(RequestFactoryInterface $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }


    /**
     * @throws ClientExceptionInterface|InvalidArgumentException
     */
    public function fetch(string $url): ResponseInterface
    {
        // Cache key
        $cacheKey = $this->getCacheKey($url);

        // Pokus o získanie odpovede z cache
        if ($this->cache !== null) {
            $cachedResponse = $this->cache->get($cacheKey);
            if (is_string($cachedResponse)) {
                return (new HttpFactory())->createResponse(200)
                    ->withBody(Utils::streamFor($cachedResponse));
            }
        }

        // Vytvorenie PSR-7 Request objektu
        $request = $this->prepareRequest($url);

        // Odoslanie requestu cez PSR-18 Client
        $response = $this->httpClient->sendRequest($request);
        $response->getBody()->getContents();

        // Prečítanie obsahu odpovede
        $body = (string)$response->getBody();

        // Uloženie do cache
        if ($this->cache !== null) {
            $this->cache->set($cacheKey, $body, $this->cacheTtl);
        }

        return (new HttpFactory())->createResponse($response->getStatusCode())
            ->withBody(Utils::streamFor($body));
    }


    private function prepareRequest(string $url): RequestInterface
    {
        // Initialize the HTTP client and request factory
        if ($this->httpClient === null) {
            $this->setHttpClient(new Client());
        }
        if ($this->requestFactory === null) {
            $this->setRequestFactory(new HttpFactory());
        }

        $request = $this->requestFactory->createRequest('GET', $url);
        $request = $request->withHeader('User-Agent', self::USER_AGENT);
        $request = $request->withHeader('Accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');

        return $request;
    }

    private function getCacheKey(string $url): string
    {
        return sprintf('%s_%s', __METHOD__, substr(md5($url), 0, 8));
    }
}
