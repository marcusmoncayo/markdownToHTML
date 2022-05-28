<?php

use PHPUnit\Framework\TestCase;
use App\lib\ParagraphTag;
use App\lib\HeaderTag;

final class ParagraphTagTest extends TestCase
{

  /**
  * @dataProvider isValidData
  **/
    public function testIsValid(string $markDown, bool $expected): void
    {
        $paragraphTag = new ParagraphTag($markDown);
        $this->assertEquals($expected, $paragraphTag->isValid(), "Incorrect isValid returned");
    }

    public function isValidData(): array
    {
        return [
        // valid cases
        "valid Paragraph case" => ["this is a valid paragraph", true],
        "valid Paragraph case one space" => [" this is a valid paragraph", true],
        "valid Paragraph case two spaces" => ["  this is a valid paragraph", true],
        "valid Paragraph case triple spaces" => ["   this is a valid paragraph", true],

        // invalid cases
        "invalid Paragraph case header tag" => ["# this is an invalid paragraph", false],
        "invalid Paragraph header tag" => ["#  this is an invalid paragraph", false],
        "invalid Paragraph anchor tag" => ["[this is an anchor](somewhere.com)", false]
        ];
    }

  /**
  * @dataProvider getStartTagData
  **/
    public function testGetStartTag(string $markdown, string $expected): void
    {
        $paragraphTag = new ParagraphTag($markdown);
        $this->assertEquals($expected, $paragraphTag->getStartTag(), "Incorrect start tag");
    }

    public function getStartTagData(): array
    {
        return [
        "p case no spaces" => ["this is a valid paragraph", "<p>"],
        "p case leading double space" => [" this is a valid paragraph", "<p>"],
        ];
    }

  /**
  * @dataProvider getEndTagData
  **/
    public function testGetEndTag(string $markdown, string $expected): void
    {
        $paragraphTag = new ParagraphTag($markdown);
        $this->assertEquals($expected, $paragraphTag->getEndTag(), "Incorrect end tag");
    }

    public function getEndTagData(): array
    {
        return [
        "p case no spaces" => ["this is a valid paragraph", "</p>"],
        "p case leading double space" => [" this is a valid paragraph", "</p>"],
        ];
    }

  /**
  * @dataProvider getContentData
  **/
    public function testGetContent(string $markdown, string $expected): void
    {
        $paragraphTag = new ParagraphTag($markdown);
        $this->assertEquals($expected, $paragraphTag->getContent(), "Incorrect content");
    }

    public function getContentData(): array
    {
        return [
        "p case no spaces" => ["this is a valid paragraph", "this is a valid paragraph"],
        "p case leading double space" => [" this is a valid paragraph", " this is a valid paragraph"],
        ];
    }

  /**
  * @dataProvider getHTMLRepresentationData
  **/
    public function testGetHTMLRepresentation(string $markdown, string $expected): void
    {
        $paragraphTag = new ParagraphTag($markdown);
        $this->assertEquals($expected, $paragraphTag->getHTMLRepresentation(), "Incorrect start tag");
    }

    public function getHTMLRepresentationData(): array
    {
        return [
        "p case no spaces" => ["this is a valid paragraph", "<p>this is a valid paragraph</p>"],
        "p case leading double space" => [" this is a valid paragraph", "<p> this is a valid paragraph</p>"],
        ];
    }

    public function testMergeTag(): void
    {
        $p1 = new ParagraphTag("first paragraph");
        $p2 = new ParagraphTag("second paragraph");

        $expected = "<p>first paragraph<br/>second paragraph</p>";

        $this->assertEquals($expected, $p1->merge($p2), "Merge is incorrect");
    }

    public function testMergeTagIncorrectTagType(): void
    {
        $p1 = new ParagraphTag("first paragraph");
        $h1 = new HeaderTag("# header tag");

        $this->assertEquals("<p>first paragraph</p>", $p1->merge($h1), "Merge is incorrect");
    }
}
