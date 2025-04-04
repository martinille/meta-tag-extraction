<?php declare(strict_types=1);

namespace MartinIlle\MetaTagExtraction;

class Tag
{
    private string $name;
    private string $value;

    public function __construct(
        private readonly string $tagName,
        private readonly string $content,
        private readonly array $attributes = [],
        private readonly string $raw = '',
    ) {
        $this->prepare();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    private function prepare(): void
    {
        if (isset($this->attributes['name']) || isset($this->attributes['http-equiv']) || isset($this->attributes['property'])) {
            $this->name = $this->attributes['name'] ?? $this->attributes['http-equiv'] ?? $this->attributes['property'];
            $this->value = $this->attributes['content'] ?? '';
        } elseif (isset($this->attributes['charset'])) {
            $this->name = 'charset';
            $this->value = $this->attributes['charset'];
        } elseif ($this->tagName === 'title') {
            $this->name = 'title';
            $this->value = $this->content;
        } elseif ($this->tagName === 'html' && isset($this->attributes['lang'])) {
            $this->name = 'lang';
            $this->value = $this->attributes['lang'];
        } elseif ($this->tagName === 'meta') {
            $this->name = 'meta';
            $this->value = $this->content;
        } elseif ($this->tagName === 'link') {
            $this->name = 'link';
            $this->value = $this->attributes['href'] ?? '';
        } elseif ($this->tagName === 'style') {
            $this->name = 'style';
            $this->value = $this->content;
        } else {
            $this->name = $this->tagName;
            $this->value = $this->content;
        }

        // Convert to lowercase
        $this->name = strtolower($this->name);
    }

    public function getAttribute(string $string)
    {
        return $this->attributes[$string] ?? null;
    }
}
