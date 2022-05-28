<?php
  namespace App\lib;

interface Tag
{

    /**
     * Validates the input string against the regex pattern
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Returns the HTML representation for the markdown
     *
     * @return string
     */
    public function getHTMLRepresentation(): string;

    /**
     * Returns HTML representation of the tag containing the merge tag
     * @param Tag $tag Tag to merge
     * @return String HTML representation
     */
    public function merge(Tag $tag): String;


    /**
     * Replaces content of markdown with HTML representation
     *
     * @param string $htmlRepresentation HTML representation
     * @return void
     */
    public function replaceContentInMarkdown(string $htmlRepresentation): void;

    /**
     * Returns the start HTML tag
     *
     * @return string
     */
    public function getStartTag(): string;

    /**
     * Returns the end HTML tag
     *
     * @return string
     */
    public function getEndTag(): string;

    /**
     * Returns the content withing the start and end tag
     *
     * @return string
     */
    public function getContent(): string;

    /**
     * Returns the regex pattern for matching
     * @return string
     */
    public function getPattern(): string;

    /**
     * Returns whether the tag can be merged with other tags
     *
     * @return bool
     */
    public function isMergeable(): bool;

    /**
     * Returns valid inner tags for the tag object
     *
     * @return array
     */
    public function getValidInnerTags(): array;

    /**
     * Returns markdown for Tag
     *
     * @return string
     */
    public function getMarkdown(): string;

    /**
     * Sets markdown for Tag
     * @param string $markdown
     * @return void
     */
    public function setMarkdown(string $markdown): void;
}
