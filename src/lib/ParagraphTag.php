<?php
  namespace App\lib;

  use Exception;

class ParagraphTag implements Tag
{

    private string $markdown;
    private string $regexPattern = "/[[:ascii:]]+/";
    private bool $canContainInnerTags = true;

    private mixed $tags = ['start' => '<p>', 'end' => '</p>'];

    private bool $isMergeable = true;

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
        $linkStartRegex = "/^\[{1}[[:ascii:]]+\]{1}\({1}[[:ascii:]]*\){1}/";
        $headerStartRegex = "/^\s{0,3}#{1,6}\s{1}/";

        if (preg_match($linkStartRegex, $this->markdown)) {
            return false;
        }

        if (preg_match($headerStartRegex, $this->markdown)) {
            return false;
        }

        return preg_match($this->regexPattern, $this->markdown) == true;
    }

    /**
     * Returns HTML Representation of the Tag
     * @throws Exception
     */
    public function getHTMLRepresentation(): string
    {
        if ($this->isValid()) {
            return $this->getStartTag() . $this->getContent() . $this->getEndTag();
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Replaces content in markdown with given string
     * @param string $htmlRepresentation
     * @return void
     */
    public function replaceContentInMarkdown(string $htmlRepresentation): void
    {
        $this->setMarkdown(str_replace($this->getContent(), $htmlRepresentation, $this->getMarkdown()));
    }

    /**
     * @throws Exception
     */
    public function merge(Tag $tag): String
    {
        if (!$this->isMergeable || !($tag instanceof ParagraphTag) || !$tag->isValid()) {
            return $this->getHTMLRepresentation();
        }

         return $this->getStartTag() . $this->getContent() . "<br/>" . $tag->getContent() . $this->getEndTag();
    }

    /**
     * @throws Exception
     */
    public function getStartTag(): string
    {
        if ($this->isValid()) {
            return $this->tags['start'];
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * @throws Exception
     */
    public function getEndTag(): string
    {
        if ($this->isValid()) {
            return $this->tags['end'];
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Returns a list of valid inner tags
     *
     * @return string[]
     */
    public function getValidInnerTags(): array
    {
        return [AnchorTag::class];
    }

    public function getPattern(): string
    {
        return $this->regexPattern;
    }

    public function canContainInnerTags(): bool
    {
        return $this->canContainInnerTags;
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

    public function getContent(): string
    {
        return $this->markdown;
    }
}
