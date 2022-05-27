<?php
  namespace App\lib;

  use Exception;

class AnchorTag implements Tag
{

    private string $markdown;

    private string $regexAnchorText = "/\[(.*?)\]/";
    private string $regexAnchorUrlText = "/\((.*?)\)/";
    private string $regexPattern = "/\[(.*?)\]\((.*?)\)/";

    private string $startTagA = "<a href=\"";
    private string $startTagB = "\">";
    private string $endTag = "</a>";

    private bool $canContainInnerTags = false;
    private bool $isMergeable = false;

    public function __construct(string $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * Verifies Tag is valid
     * @return bool
     */
    public function isValid(): bool
    {
        return preg_match($this->regexPattern, $this->markdown) == true;
    }

    /**
     * Returns HTML Representation of the Tag
     * @throws Exception
     */
    public function getHTMLRepresentation(): string
    {
        $anchorRepresentation = $this->getStartTag() . $this->getContent() . $this->getEndTag();
        return preg_replace($this->regexPattern, $anchorRepresentation, $this->markdown, 1);
    }

    /**
     *
     * @throws Exception
     */
    public function replaceContentInMarkdown(string $markdown): void
    {
        throw new Exception("Cannot replace content of an Anchor Tag at this time");
    }

    public function merge(Tag $tag): string
    {
        throw new Exception("Cannot merge Anchor Tag");
    }

    /**
     * Returns leading HTML tag
     *
     * @return string
     * @throws Exception
     */
    public function getStartTag(): string
    {
        $matches = [];

        if ($this->isValid()) {
            preg_match($this->regexAnchorUrlText, $this->markdown, $matches);
            $url = substr($matches[0], 1, strlen($matches[0])-2);
            return $this->startTagA . $url . $this->startTagB;
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Returns content within Tag
     *
     * @return string
     * @throws Exception
     */
    public function getContent(): string
    {
        $matches = [];

        if ($this->isValid()) {
            preg_match($this->regexAnchorText, $this->markdown, $matches);
            $text = substr($matches[0], 1, strlen($matches[0])-2);
            return $text;
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Returns trailing HTML tag
     *
     * @return string
     * @throws Exception
     */
    public function getEndTag(): string
    {
        if ($this->isValid()) {
            return $this->endTag;
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    public function getValidInnerTags(): array
    {
        return [];
    }

    public function canContainInnerTags(): bool
    {
        return $this->canContainInnerTags;
    }

    public function getPattern(): string
    {
        return $this->regexPattern;
    }

    public function isMergeable(): bool
    {
        return $this->isMergeable;
    }

    public function getMarkdown(): string
    {
        return $this->markdown;
    }

    public function setMarkdown(string $markdown): void
    {
        $this->markdown = $markdown;
    }
}
