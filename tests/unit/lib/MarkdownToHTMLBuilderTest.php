<?php

use PHPUnit\Framework\TestCase;
use App\lib\MarkdownToHTMLBuilder;
use App\lib\HeaderTag;
use App\lib\AnchorTag;
use App\lib\ParagraphTag;

final class MarkdownToHTMLBuilderTest extends TestCase
{
  /**
  * @dataProvider mailchimpTestCases
  */
    public function testConverterWithMailchimpInput($markdown, $expected): void
    {
        $markdownConverter = new MarkdownToHTMLBuilder();
        $this->assertEquals($expected, $markdownConverter->build($markdown));
    }

    public function mailchimpTestCases(): array
    {
        return [
        "test case 1" =>
        [
        "# Sample Document" . '\n' . "Hello!" . '\n' . '\n' . "This is sample markdown for the [Mailchimp](https://www.mailchimp.com)homework assignment.",
        "<h1>Sample Document</h1><p>Hello!</p><p>This is sample markdown for the <a href=\"https://www.mailchimp.com\">Mailchimp</a>homework assignment.</p>"
        ],
        "test case 2" => [ "# Header one" . '\n' . "Hello there " . '\n' . '\n' . "How are you?" . '\n' . "What's going on?" . '\n' . "## Another Header" . '\n' . "This is a paragraph [with an inline link](http://google.com). Neat, eh?" . '\n' . "## This is a header [with a link](http://yahoo.com)",
        "<h1>Header one</h1><p>Hello there </p><p>How are you?<br/>What's going on?</p><h2>Another Header</h2><p>This is a paragraph <a href=\"http://google.com\">with an inline link</a>. Neat, eh?</p><h2>This is a header <a href=\"http://yahoo.com\">with a link</a></h2>"
        ]
        ];
    }

  /**
  * @dataProvider getRepresentationData
  */
    public function testMarkdownConverterGetHTMLRepresentation(string $markdown, string $expected, string $classname): void
    {
        $tag = new $classname($markdown);
        $converter = new MarkdownToHTMLBuilder();
        $this->assertEquals($expected, $converter->buildHTMLTag($tag)->getHTMLRepresentation());
    }

    public function getRepresentationData(): array
    {
        return [
        "header" => ["# this is a header", "<h1>this is a header</h1>", HeaderTag::class],
        "anchor" => ["[somewhere](www.somewhere.com)", "<a href=\"www.somewhere.com\">somewhere</a>", AnchorTag::class],
        "paragraph" =>["this is a paragraph with an [Anchor](www.somewhere.com)", "<p>this is a paragraph with an <a href=\"www.somewhere.com\">Anchor</a></p>", ParagraphTag::class],
        ];
    }

  /**
  * @dataProvider getBuildHTMLData
  */
    public function testBuildHTML(string $markdown, string $expected): void
    {
        $converter = new MarkdownToHTMLBuilder();
        $this->assertEquals($expected, $converter->build($markdown));
    }

    public function getBuildHTMLData(): array
    {
        return [
        "header" => ["# this is a header", "<h1>this is a header</h1>"],
        "anchor" => ["[somewhere](www.somewhere.com)", "<a href=\"www.somewhere.com\">somewhere</a>"],
        "paragraph with anchor" =>["this is a paragraph with an [Anchor](www.somewhere.com)", "<p>this is a paragraph with an <a href=\"www.somewhere.com\">Anchor</a></p>"],
        "paragraph with new line" => ["this is a paragraph " . '\n' . " with a new line", "<p>this is a paragraph <br/> with a new line</p>"],
        "paragraph with new line" => ["this is a paragraph" . '\n' . "with a new line", "<p>this is a paragraph<br/>with a new line</p>"],
        "paragraph with two new lines" => ["this is a paragraph" . '\n' . '\n' . "with a new line", "<p>this is a paragraph</p><p>with a new line</p>"],
        "paragraph with three new lines" => ["this is a paragraph" . '\n' . '\n' . '\n' . "with a new line", "<p>this is a paragraph</p><p>with a new line</p>"],
        "blank line" => ["", ""],
        "double anchor" => ["this [is](one anchor) and this [is](another)","<p>this <a href=\"one anchor\">is</a> and this <a href=\"another\">is</a></p>"]
        ];
    }

    public function testConverterMergeTags(): void
    {
        $input = "this is the first paragraph" . '\n' . "this is the second paragraph";

        $expected = "<p>this is the first paragraph<br/>this is the second paragraph</p>";

        $markdownConverter = new MarkdownToHTMLBuilder();
        $this->assertEquals($expected, $markdownConverter->build($input), "Incorrect Merge HTML is incorrect");
    }
}
