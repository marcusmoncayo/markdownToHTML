<?php

use PHPUnit\Framework\TestCase;
use App\lib\HeaderTag;

final class HeaderTagTest extends TestCase {

  /**
  * @dataProvider isValidData
  **/
  public function testIsValid(string $markDown, bool $expected): void
  {
    $headerTag = new HeaderTag($markDown);
    $this->assertEquals($expected, $headerTag->isValid(), "Incorrect isValid returned");
  }

  public function isValidData(): array
  {
    return [
      // valid cases
      "valid H1 case" => ["# this is a valid header", true],
      "valid H2 case" => ["## this is a valid header", true],
      "valid H3 case" => ["### this is a valid header", true],
      "valid H4 case" => ["#### this is a valid header", true],
      "valid H5 case" => ["##### this is a valid header", true],
      "valid H6 case" => ["###### this is a valid header", true],
      "valid Header case with 3 spaces" => ["   # this is a valid header", true],
      "valid Header case with anchor tag" => ["# [A Link](www.alink.com)", true],

      // invalid cases
      "invalid Header case no header tag" => ["this is an invalid header", false],
      "invalid Header case 4 spaces" => ["    this is an invalid header", false],
    ];
  }

  /**
  * @dataProvider getStartTagData
  **/
  public function testGetStartTag(string $markdown, string $expected): void
  {
    $headerTag = new HeaderTag($markdown);
    $this->assertEquals($expected, $headerTag->getStartTag(), "Incorrect start tag");
  }

  public function getStartTagData(): array
  {
    return [
      // valid cases
      "H1 case" => ["# this is a valid header", "<h1>"],
      "H2 case" => ["## this is a valid header", "<h2>"],
      "H3 case" => ["### this is a valid header", "<h3>"],
      "H4 case" => ["#### this is a valid header", "<h4>"],
      "H5 case" => ["##### this is a valid header", "<h5>"],
      "H6 case" => ["###### this is a valid header", "<h6>"],
    ];
  }

  /**
  * @dataProvider getEndTagData
  **/
  public function testGetEndTag(string $markdown, string $expected): void
  {
    $headerTag = new HeaderTag($markdown);
    $this->assertEquals($expected, $headerTag->getEndTag(), "Incorrect end tag");
  }

  public function getEndTagData(): array {
    return [
      // valid cases
      "H1 case" => ["# this is a valid header", "</h1>"],
      "H2 case" => ["## this is a valid header", "</h2>"],
      "H3 case" => ["### this is a valid header", "</h3>"],
      "H4 case" => ["#### this is a valid header", "</h4>"],
      "H5 case" => ["##### this is a valid header", "</h5>"],
      "H6 case" => ["###### this is a valid header", "</h6>"],
    ];
  }

  public function testGetContent(): void
  {
    $markdown = "# this is a valid header";
    $headerTag = new HeaderTag($markdown);
    $this->assertEquals("this is a valid header", $headerTag->getContent(), "Incorrect content");
  }

  /**
  * @dataProvider getHTMLRepresentationData
  **/
  public function testGetHTMLRepresentation(string $markdown, string $expected): void
  {
    $headerTag = new HeaderTag($markdown);
    $this->assertEquals($expected, $headerTag->getHTMLRepresentation(), "Incorrect start tag");
  }

  public function getHTMLRepresentationData(): array
  {
    return [
      // valid cases
      "H1 case" => ["# this is a valid header", "<h1>this is a valid header</h1>"],
      "H2 case" => ["## this is a valid header", "<h2>this is a valid header</h2>"],
      "H3 case" => ["### this is a valid header", "<h3>this is a valid header</h3>"],
      "H4 case" => ["#### this is a valid header", "<h4>this is a valid header</h4>"],
      "H5 case" => ["##### this is a valid header", "<h5>this is a valid header</h5>"],
      "H6 case" => ["###### this is a valid header", "<h6>this is a valid header</h6>"],
      "H1 case with #" => ["# this is a # valid header", "<h1>this is a # valid header</h1>"],
      "H1 case with anchor" => ["# this is a valid header with [an anchor](link)", "<h1>this is a valid header with [an anchor](link)</h1>"],
    ];
  }

  /**
  * @dataProvider replaceContentInMarkdownData
  */
  public function testReplaceContentInMarkdown(string $markdown, string $expected): void
  {
    $replacementText ="this is replacement text";
    $headerTag = new HeaderTag($markdown);
    $headerTag->replaceContentInMarkdown($replacementText);

    $this->assertEquals($expected, $headerTag->getMarkdown(), "Markdown is incorrect after replacement text change");
  }

  public function replaceContentInMarkdownData(): array
  {
      return [
        "H1 case" => ["# this is a valid header", "# this is replacement text"],
        "H2 case" => ["## this is a valid header", "## this is replacement text"],
        "H3 case" => ["### this is a valid header", "### this is replacement text"],
        "H4 case" => ["#### this is a valid header", "#### this is replacement text"],
        "H5 case" => ["##### this is a valid header", "##### this is replacement text"],
        "H6 case" => ["###### this is a valid header", "###### this is replacement text"],
        "H1 case with #" => ["# this is a # valid header", "# this is replacement text"]
      ];
  }
}
