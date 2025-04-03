<?php

namespace MetaTagExtraction\Tests;

use MetaTagExtraction\HtmlParserService;
use MetaTagExtraction\Tag;
use PHPUnit\Framework\TestCase;

final class HtmlParserServiceTest extends TestCase
{
    /**
     * @dataProvider htmlProvider
     */
    public function testParsesHtmlCorrectly(string $html, int $expectedCount, array $expectedTags): void
    {
        $parser = new HtmlParserService($html);
        $tags = $parser->parseMetaTags();

        $this->assertCount($expectedCount, $tags);

        foreach ($expectedTags as $index => $expectedTag) {
            $this->assertInstanceOf(Tag::class, $tags[$index]);
            $this->assertEquals($expectedTag['tagName'], $tags[$index]->getTagName());
            $this->assertEquals($expectedTag['name'], $tags[$index]->getName());
            $this->assertEquals($expectedTag['value'], $tags[$index]->getValue());
            $this->assertCount(count($expectedTag['attributes']), $tags[$index]->getAttributes());

            foreach ($expectedTag['attributes'] as $attrName => $attrValue) {
                $this->assertEquals($attrValue, $tags[$index]->getAttribute($attrName));
            }

            $this->assertEquals($expectedTag['raw'], $tags[$index]->getRaw());
        }
    }

    public static function htmlProvider(): iterable
    {
        yield 'Meta tags' => [
            '<html><head><meta name="description" content="Test Description"><meta name="custom-tag-1" content="lorem ipsum" attribute-1="lorem" attribute-2="ipsum"></head></html>',
            2,
            [
                [
                    'tagName' => 'meta',
                    'name' => 'description',
                    'value' => 'Test Description',
                    'attributes' => ['name' => 'description', 'content' => 'Test Description'],
                    'raw' => '<meta name="description" content="Test Description">',
                ],
                [
                    'tagName' => 'meta',
                    'name' => 'custom-tag-1',
                    'value' => 'lorem ipsum',
                    'attributes' => ['name' => 'custom-tag-1','content' => 'lorem ipsum', 'attribute-1' => 'lorem', 'attribute-2' => 'ipsum'],
                    'raw' => '<meta name="custom-tag-1" content="lorem ipsum" attribute-1="lorem" attribute-2="ipsum">',
                ],
            ],
        ];

        yield 'Title tag' => [
            '<html><head><title>Test Title</title></head></html>',
            1,
            [
                [
                    'tagName' => 'title',
                    'name' => 'title',
                    'value' => 'Test Title',
                    'attributes' => [],
                    'raw' => '<title>Test Title</title>',
                ],
            ],
        ];

        yield 'No meta tags' => [
            '<html><head></head><body><p>No meta tags here</p></body></html>',
            0,
            [],
        ];
    }

    public function testHandlesEmptyHtml(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new HtmlParserService('');
    }
}
