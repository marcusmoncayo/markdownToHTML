<?php

  namespace App\lib;

  use Exception;

class MarkdownToHTMLBuilder
{

    const SKIP = "***SKIP***";
    const NEW_LINE = '\n';

    private array $startTags = [HeaderTag::class];
    private array $innerTags = [ParagraphTag::class,AnchorTag::class];
    private array $htmlTags = [];

    /**
     * Process a string of markdown
     *
     * @param string $markdown
     * @return string
     * @throws Exception
     */
    public function build(string $markdown): string
    {
        $lines = explode(self::NEW_LINE, $markdown);

        foreach ($lines as $line) {
            if ($line == '') {
                $this->htmlTags[] = self::SKIP;
                continue;
            }

            if ($this->processTags($this->startTags, $line, $this->htmlTags)) {
                continue;
            }

            $this->processTags($this->innerTags, $line, $this->htmlTags);
        }

        return $this->getHTMLFromTags($this->htmlTags);
    }

    /**
     * Process a line of markdawn against a list of tag classes
     *
     * @param array $tagClasses
     * @param string $markdown
     * @param array $htmlTags
     * @return bool
     */
    private function processTags(array $tagClasses, string $markdown, array &$htmlTags): bool
    {
        foreach ($tagClasses as $tagClass) {
            $tag = new $tagClass($markdown);

            if ($tag->isValid()) {
                $htmlTags[] = $this->buildHTMLTag($tag);
                return true;
            }
        }

        return false;
    }

    /**
     * Recursive function to process the Tag to ensure proper nesting of elements
     *
     * @param \App\lib\Tag $tag
     * @return \App\lib\Tag
     */
    public function buildHTMLTag(Tag $tag): Tag
    {
        if (!$tag->isValid()) {
            return $tag;
        }

        // validate and update the markdown for all the inner tags
        foreach ($tag->getValidInnerTags() as $innerTagClass) {
            // exclude the markdown from the parent tag
            $innerTag = new $innerTagClass($tag->getContent());

            if ($innerTag->isValid()) {

                // recursively call to ensure all inner tags are processed
                if ($innerTag->getValidInnerTags()) {
                    $this->buildHTMLTag($innerTag);
                }

                // if there is a valid inner tag replace the content for the parent tag
                $tag->replaceContentInMarkdown($innerTag->getHTMLRepresentation());

                return $this->buildHTMLTag($tag);
            }
        }

        return $tag;
    }

    /**
     * Returns HTML from a list of Tags
     *
     * @param array $htmlTags
     * @return string
     * @throws Exception
     */
    private function getHTMLFromTags(array $htmlTags): string
    {
        $result = [];

        if (count($htmlTags) == 0) {
            throw new Exception("Markdown is not valid. Please Submit valid markdown.");
        }

        for ($i = count($htmlTags)-1; $i >= 0; $i--) {
          // If it is not an instance of tag move on
            if (!$htmlTags[$i] instanceof Tag) {
                continue;
            }

          // If this is the first tag, add to results
            if ($i == 0) {
                $result[] = $htmlTags[$i]->getHTMLRepresentation();
                continue;
            }

          // If the html tag is mergeable, merge and add to results
            if ($htmlTags[$i-1] instanceof Tag && $htmlTags[$i]->isMergeable() && $htmlTags[$i-1]->isMergeable()) {
                $result[] = $htmlTags[$i-1]->merge($htmlTags[$i]);
                $i--;
                continue;
            }

          // If the html tag is not mergeable add to results
            $result[] = $htmlTags[$i]->getHTMLRepresentation();
        }

        return implode("", array_reverse($result));
    }
}
