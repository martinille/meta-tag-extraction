<?php

namespace MetaTagExtraction\Tests;

use MetaTagExtraction\Tag;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TagTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     */
    public function testTagCreation(
        string $tagName,
        string $content,
        array $attributes,
        string $raw,
        string $expectedName,
        string $expectedValue,
    ): void {
        $tag = new Tag($tagName, $content, $attributes, $raw);

        $this->assertSame($tagName, $tag->getTagName());
        $this->assertSame($content, $tag->getContent());
        $this->assertSame($attributes, $tag->getAttributes());
        $this->assertSame($raw, $tag->getRaw());
        $this->assertSame($expectedName, $tag->getName());
        $this->assertSame($expectedValue, $tag->getValue());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidTagCreation(
        string $tagName,
        string $content,
        array $attributes,
        string $raw,
        string $expectedName,
        string $expectedValue,
    ): void {
        $tag = new Tag($tagName, $content, $attributes, $raw);

        $this->assertSame($tagName, $tag->getTagName());
        $this->assertSame($content, $tag->getContent());
        $this->assertSame($attributes, $tag->getAttributes());
        $this->assertSame($raw, $tag->getRaw());
        $this->assertSame($expectedName, $tag->getName());
        $this->assertSame($expectedValue, $tag->getValue());
    }

    public static function validDataProvider(): \Generator
    {
        yield 'meta description' => [
            'tagName' => 'meta',
            'content' => 'example content',
            'attributes' => [
                'name' => 'description',
                'content' => 'example content',
            ],
            'raw' => '<meta name="description" content="example content">',
            'expectedName' => 'description',
            'expectedValue' => 'example content',
        ];

        yield 'meta charset' => [
            'tagName' => 'meta',
            'content' => '',
            'attributes' => [
                'charset' => 'UTF-8',
            ],
            'raw' => '<meta charset="UTF-8">',
            'expectedName' => 'charset',
            'expectedValue' => 'UTF-8',
        ];

        yield 'title' => [
            'tagName' => 'title',
            'content' => 'Example Title',
            'attributes' => [],
            'raw' => '<title>Example Title</title>',
            'expectedName' => 'title',
            'expectedValue' => 'Example Title',
        ];

        yield 'link' => [
            'tagName' => 'link',
            'content' => 'style.css',
            'attributes' => [
                'rel' => 'stylesheet',
                'href' => 'style.css',
            ],
            'raw' => '<link rel="stylesheet" href="style.css">',
            'expectedName' => 'link',
            'expectedValue' => 'style.css',
        ];

        yield 'script' => [
            'tagName' => 'script',
            'content' => 'console.log("Hello, World!");',
            'attributes' => [
                'src' => 'script.js',
                'type' => 'text/javascript',
            ],
            'raw' => '<script src="script.js" type="text/javascript">console.log("Hello, World!");</script>',
            'expectedName' => 'script',
            'expectedValue' => 'console.log("Hello, World!");',
        ];

        yield 'style' => [
            'tagName' => 'style',
            'content' => 'body { background-color: #fff; }',
            'attributes' => [],
            'raw' => '<style>body { background-color: #fff; }</style>',
            'expectedName' => 'style',
            'expectedValue' => 'body { background-color: #fff; }',
        ];

        yield 'language' => [
            'tagName' => 'html',
            'content' => '',
            'attributes' => [
                'lang' => 'en',
            ],
            'raw' => '<html lang="en"></html>',
            'expectedName' => 'lang',
            'expectedValue' => 'en',
        ];
    }

    public static function invalidDataProvider(): \Generator
    {
        yield 'empty tag' => [
            'tagName' => '',
            'content' => '',
            'attributes' => [],
            'raw' => '',
            'expectedName' => '',
            'expectedValue' => '',
        ];

        yield 'invalid tag name' => [
            'tagName' => 'invalid',
            'content' => '',
            'attributes' => [],
            'raw' => '<invalid></invalid>',
            'expectedName' => 'invalid',
            'expectedValue' => '',
        ];
    }
}
