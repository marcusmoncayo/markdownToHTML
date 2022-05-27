<?php
  namespace App\lib;

interface Tag
{
    /** returns the start HTML tag */
    public function getStartTag(): string;

    /** returns the end HTML tag */
    public function getEndTag(): string;

    /** returns the content withing the start and end tag */
    public function getContent(): string;

    /** returns the HTML representation for the markdown */
    public function getHTMLRepresentation(): string;

    /** replaces the inner text of the markdown */
    public function replaceContentInMarkdown(string $htmlRepresentation): void;

    /** returns the regex pattern for matching */
    public function getPattern(): string;

    /** validates the input string against the regex pattern */
    public function isValid(): bool;

    /** returns whether or not the tag can be merged with other tags */
    public function isMergeable(): bool;

    /** returns merged HTML Tag*/
    public function merge(Tag $tag): String;

    /** returns whether or not the tag can contain inner tags */
    public function canContainInnerTags(): bool;

    /** returns valid inner tags for the tag object */
    public function getValidInnerTags(): array;
}
