<?php

namespace App\lib;

class StrongTagClass implements Tag
{

    private string $markdown;

    private string $regexPattern = "\*\*\*(.*?)\*\*\*";
    private array $tags = ['start' => '<strong><em>', 'end' => '</em></strong>'];

    public function __construct(string $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return preg_match(Utilities::PHP_REGEX_DELIMETER . $this->regexPattern . Utilities::PHP_REGEX_DELIMETER, $this->markdown);
    }

    /**
     * @inheritDoc
     */
    public function getHTMLRepresentation(): string
    {
        if ($this->isValid()) {

            $tempResult = preg_replace(
                Utilities::PHP_REGEX_DELIMETER . $this->regexPattern . Utilities::PHP_REGEX_DELIMETER,
                " ******to replace***** ",
                $this->getMarkdown()
            );

            $tagReplacement = $this->getStartTag() . $this->getContent() . $this->getEndTag();

            return str_replace(" ******to replace***** ", $tagReplacement, $tempResult);
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * @inheritDoc
     */
    public function merge(Tag $tag): string
    {
        // TODO: Implement merge() method.
    }

    /**
     * @inheritDoc
     */
    public function replaceContentInMarkdown(string $htmlRepresentation): void
    {
        // TODO: Implement replaceContentInMarkdown() method.
    }

    /**
     * @inheritDoc
     */
    public function getStartTag(): string
    {
        if ($this->isValid()) {
            return $this->tags['start'];
        }

        throw new Exception("Markdown is not valid, please validate.");    }

    /**
     * @inheritDoc
     */
    public function getEndTag(): string
    {
        if ($this->isValid()) {
            return $this->tags['end'];
        }

        throw new Exception("Markdown is not valid, please validate.");
    }


    /**
     * Returns content within Header Tag
     *
     * @return string
     */
    public function getContent(): string
    {
        if($this->isValid()) {
            $matches = [];
            preg_match(Utilities::PHP_REGEX_DELIMETER . $this->getPattern() . Utilities::PHP_REGEX_DELIMETER, $this->markdown, $matches);

            return $matches[1];
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * @inheritDoc
     */
    public function getPattern(): string
    {
        return $this->regexPattern;
    }

    /**
     * @inheritDoc
     */
    public function isMergeable(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getValidInnerTags(): array
    {
        return InnerTags::getValidInnerTagsForClass(self::class);
    }

    /**
     * @inheritDoc
     */
    public function getMarkdown(): string
    {
        return $this->markdown;
    }

    /**
     * @inheritDoc
     */
    public function setMarkdown(string $markdown): void
    {
        $this->markdown = $markdown;
    }
}