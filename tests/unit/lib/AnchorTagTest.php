<?php

use PHPUnit\Framework\TestCase;
use App\lib\AnchorTag;

final class AnchorTagTest extends TestCase
{

  /**
  * @dataProvider isValidData
  */
    public function testIsValid(string $markDown, bool $expected): void
    {
        $anchorTag = new AnchorTag($markDown);
        $this->assertEquals($anchorTag->isValid(), $expected, "Incorrect isValid returned");
    }

    public function isValidData(): array
    {
        return [
        // valid cases
        "Valid Anchor Tag" => ["[A link](alink.com)", true],
        "Valid Anchor Tag characters before" => ["asdf[A link](alink.com)", true],
        "Valid Anchor Tag characters after" => ["[A link](alink.com)asdf", true],
        "Valid Anchor Tag characters before and after" => ["asdf[A link](alink.com)asdf", true],
        "Valid Anchor Tag No URL" => ["[A link]()", true],

        // invalid cases
        "Invalid Anchor Tag string" => ["asdf", false],
        "Invalid Anchor Tag No parens" => ["asdf[A link]asdf", false],
        "Invalid Anchor Tag No brackets" => ["(alink.com)", false],
        ];
    }

  /**
  * @dataProvider getStartTagData
  */
    public function testGetStartTag($markDown, $expected): void
    {
        $anchorTag = new AnchorTag($markDown);
        $this->assertEquals($expected, $anchorTag->getStartTag(), "Incorrect start tag");
    }

    public function getStartTagData(): array
    {
        return [
        "valid anchor tag with url" => ["[A link](alink.com)", "<a href=\"alink.com\">"],
        "valid anchor tag no url" => ["[A link]()", "<a href=\"\">"],
        "valid anchor tag with random alpha numeric" => ["[A link](asdf1234)", "<a href=\"asdf1234\">"]
        ];
    }

  /**
  * @dataProvider getEndTagData
  **/
    public function testGetEndTag(string $markdown): void
    {
        $headerTag = new AnchorTag($markdown);
        $this->assertEquals("</a>", $headerTag->getEndTag(), "Incorrect end tag");
    }

    public function getEndTagData(): array
    {
        return [
        // valid cases
        "Valid Anchor Tag" => ["[A link](alink.com)"],
        "Valid Anchor Tag characters before" => ["asdf[A link](alink.com)"],
        "Valid Anchor Tag characters after" => ["[A link](alink.com)asdf"],
        "Valid Anchor Tag characters before and after" => ["asdf[A link](alink.com)asdf"],
        "Valid Anchor Tag No URL" => ["[A link]()"]
        ];
    }

    public function testGetContent(): void
    {
        $markdown = "[A link](alink.com)";
        $anchorTag = new AnchorTag($markdown);
        $this->assertEquals("A link", $anchorTag->getContent(), "Incorrect content");
    }

  /**
  * @dataProvider getHTMLRepresentationData
  **/
    public function testGetHTMLRepresentation($markdown, $expected): void
    {
        $anchorTag = new AnchorTag($markdown);
        $this->assertEquals($expected, $anchorTag->getHTMLRepresentation(), "Incorrect start tag");
    }

    public function getHTMLRepresentationData(): array
    {
        return [
        "valid anchor tag with url" => ["[A link](alink.com)", "<a href=\"alink.com\">A link</a>"],
        "valid anchor tag no url" => ["[A link]()", "<a href=\"\">A link</a>"],
        "valid anchor tag with random alpha numeric" => ["[A link](asdf1234)", "<a href=\"asdf1234\">A link</a>"]
        ];
    }
}
