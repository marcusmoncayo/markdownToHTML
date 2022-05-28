<?php
  namespace App\lib;

  use Exception;

class ParagraphTag implements Tag
{

    private string $markdown;
    private string $regexPattern = "/[[:ascii:]]+/";

    private mixed $tags = ['start' => '<p>', 'end' => '</p>'];

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
        $tagClasses = array_diff(Utilities::getAllTagClasses(), [ParagraphTag::class]);

        foreach ($tagClasses as $tagClass) {
            $tag = new $tagClass($this->getMarkdown());
            $pattern = Utilities::PHP_REGEX_STARTS_WITH_DELIMETER . $tag->getPattern() . Utilities::PHP_REGEX_DELIMETER;

            $isValid = preg_match($pattern, $this->getMarkdown());

            if ($isValid) {
                return false;
            }
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
     * Returns HTML representation of the tag containing the merge tag
     *
     * @throws Exception
     */
    public function merge(Tag $tag): String
    {
        if (!$this->isMergeable() || !($tag instanceof ParagraphTag) || !$tag->isValid()) {
            return $this->getHTMLRepresentation();
        }

        return $this->getStartTag() . $this->getContent() . "<br/>" . $tag->getContent() . $this->getEndTag();
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
     * Returns content within Tag
     *
     * @return string
     * @throws Exception
     */
    public function getContent(): string
    {
        return $this->markdown;
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
        return true;
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
