<?php
  namespace App\lib;

  use Exception;

class AnchorTag implements Tag
{
    private string $markdown;

    private string $regexAnchorText = "\[(.*?)\]";
    private string $regexAnchorUrlText = "\((.*?)\)";
    private string $regexPattern = "\[(.*?)\]\((.*?)\)";

    private string $startTagA = "<a href=\"";
    private string $startTagB = "\">";
    private string $endTag = "</a>";

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
        return preg_match(Utilities::PHP_REGEX_DELIMETER . $this->regexPattern . Utilities::PHP_REGEX_DELIMETER, $this->markdown) == true;
    }

    /**
     * Returns HTML Representation of the Tag
     * @throws Exception
     */
    public function getHTMLRepresentation(): string
    {
        if ($this->isValid()) {
            $anchorRepresentation = $this->getStartTag() . $this->getContent() . $this->getEndTag();
            return preg_replace(Utilities::PHP_REGEX_DELIMETER . $this->regexPattern . Utilities::PHP_REGEX_DELIMETER, $anchorRepresentation, $this->markdown, 1);
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Cannot merge AnchorTags
     *
     * @param Tag $tag
     * @return string
     * @throws Exception
     */
    public function merge(Tag $tag): string
    {
        throw new Exception("Cannot merge Anchor Tag");
    }

    /**
     * Cannot replace content in AnchorTags
     * @throws Exception
     */
    public function replaceContentInMarkdown(string $htmlRepresentation): void
    {
        $this->setMarkdown(str_replace($this->getContent(), $htmlRepresentation, $this->getMarkdown()));
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
            preg_match(Utilities::PHP_REGEX_DELIMETER . $this->regexAnchorUrlText . Utilities::PHP_REGEX_DELIMETER, $this->markdown, $matches);
            $url = substr($matches[0], 1, strlen($matches[0])-2);
            return $this->startTagA . $url . $this->startTagB;
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
            preg_match(Utilities::PHP_REGEX_DELIMETER . $this->regexAnchorText . Utilities::PHP_REGEX_DELIMETER, $this->markdown, $matches);
            $text = substr($matches[0], 1, strlen($matches[0])-2);
            return $text;
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Returns regex Pattern
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->regexPattern;
    }

    /**
     * Returns true if tag is mergeable
     *
     * @return bool
     */
    public function isMergeable(): bool
    {
        return false;
    }

    /**
     * Returns a list of valid inner tags
     *
     * @return string[]
     */
    public function getValidInnerTags(): array
    {
        return InnerTags::getValidInnerTagsForClass(self::class);
    }

    /**
     * Returns markdown
     *
     * @return string
     */
    public function getMarkdown(): string
    {
        return $this->markdown;
    }

    /**
     * Sets markdown
     *
     * @param string $markdown
     * @return void
     */
    public function setMarkdown(string $markdown): void
    {
        $this->markdown = $markdown;
    }
}
