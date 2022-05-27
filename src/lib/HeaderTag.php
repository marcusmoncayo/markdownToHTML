<?php
  namespace App\lib;

  use Exception;

class HeaderTag implements Tag
{

    private string $markdown;

    private string $regexPattern = "/^\s{0,3}#{1,6}\s{1}/";
    private string $headerType = "";

    private bool $canContainInnerTags = true;
    private bool $isMergeable = false;

    private mixed $tags = [
    "#" => ['start' => '<h1>', 'end' => '</h1>'],
    "##" => ['start' => '<h2>', 'end' => '</h2>'],
    "###" => ['start' => '<h3>', 'end' => '</h3>'],
    "####" => ['start' => '<h4>', 'end' => '</h4>'],
    "#####" => ['start' => '<h5>', 'end' => '</h5>'],
    "######" => ['start' => '<h6>', 'end' => '</h6>']
    ];

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
        $matches = [];

        if (preg_match($this->regexPattern, $this->markdown, $matches)) {
            $this->headerType = trim($matches[0]);
            return true;
        }

        return false;
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

    public function merge(Tag $tag): string
    {
        throw new Exception("Cannot Merge Header Tag");
    }

    /**
     * Returns leading HTML tag
     *
     * @return string
     * @throws Exception
     */
    public function getStartTag(): string
    {
        if ($this->isValid()) {
            return $this->tags[$this->headerType]['start'];
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
        return preg_replace($this->getPattern(), '', $this->markdown);
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
            return $this->tags[$this->headerType]['end'];
        }

        throw new Exception("Markdown is not valid, please validate.");
    }

    /**
     * Replaces content in markdown with given string
     * @param string $htmlRepresentation
     * @return void
     */
    public function replaceContentInMarkdown($htmlRepresentation): void
    {
        $this->setMarkdown(str_replace($this->getContent(), $htmlRepresentation, $this->getMarkdown()));
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
