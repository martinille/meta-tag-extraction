<?php

declare(strict_types=1);

namespace MetaTagExtraction;

class HtmlParserService
{
    /**
     * @var array<Tag>
     */
    private array $tags = [];

    public function __construct(
        private readonly string $html,
    ) {
        if (empty(trim($this->html))) {
            throw new \InvalidArgumentException('HTML content cannot be empty.');
        }
    }

    /**
     * Parses the HTML and extracts meta tags.
     * @return array<Tag>
     */
    public function parseMetaTags(): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->html);

        // Extract meta tags
        foreach ($dom->getElementsByTagName('meta') as $meta) {
            $tag = new Tag(
                tagName: 'meta',
                content: $meta->textContent,
                attributes: $this->getAttributes($meta),
                raw: $meta->ownerDocument->saveHTML($meta)
            );
            $this->tags[] = $tag;
        }

        // Extract title tags
        foreach ($dom->getElementsByTagName('title') as $title) {
            $this->tags[] = new Tag(
                tagName: 'title',
                content: $title->textContent,
                attributes: $this->getAttributes($title),
                raw: $title->ownerDocument->saveHTML($title)
            );
        }

        // Extract language tags
        foreach ($dom->getElementsByTagName('html') as $html) {
            if ($html->hasAttribute('lang')) {
                $this->tags[] = new Tag(
                    tagName: 'html',
                    content: $html->getAttribute('lang'),
                    attributes: $this->getAttributes($html),
                    raw: $html->ownerDocument->saveHTML($html)
                );
            }
        }

        return $this->tags;
    }

    private function getAttributes(\DOMElement $element): array
    {
        $attributes = [];
        foreach ($element->attributes as $attr) {
            $attributes[$attr->nodeName] = $attr->nodeValue;
        }

        return $attributes;
    }
}
