# Markdown Converter

The following repo is a light weight Symfony api service created for converting Markdown to HTML.
Markdown support for Header, Paragraph, and Anchor Tags.

## Requirements
- Homebrew ```/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"```
- Php ```brew install php``` (Optional should be handled by Composer)
- Composer ```brew install composer```
- Symfony ```brew install symfony-cli/tap/symfony-cli```

## Setup
- Clone Repo
- Open terminal and navigate to root directory
- run command ```composer install```
- start service ``` symfony server:start```

## Usage
- The API only support GET requests
- The api end point is as follows:
  - ```localhost:8000?markdown=[url_encoded_markdown]```
- Result will display in HTML format along with HTML markup

## Examples
[Homework Example 1](http://127.0.0.1:8000/markdownToHTML?markdown=%3Ch1%3ESample+Document%3C%2Fh1%3E%3Cp%3EHello%21%3C%2Fp%3E%3Cp%3EThis+is+sample+markdown+for+the+%3Ca+href%3D%22https%3A%2F%2Fwww.mailchimp.com%22%3EMailchimp%3C%2Fa%3E+homework+assignment.%3C%2Fp%3E)

[Homework Example 2](http://127.0.0.1:8000/markdownToHTML?markdown=%23+Header+one%0AHello+there+%0A%0AHow+are+you%3F%0AWhat%27s+going+on%3F%0A%23%23+Another+Header%0AThis+is+a+paragraph+%5Bwith+an+inline+link%5D%28http%3A%2F%2Fgoogle.com%29.+Neat%2C+eh%3F%0A%23%23+This+is+a+header+%5Bwith+a+link%5D%28http%3A%2F%2Fyahoo.com%29)

## Description
- All class files are found at ```src/lib```
- All Unit Tests are found at ```tests/unit/lib```
- Homework test cases found at ``` tests/unit/lib/MarkdownToHTMLBuilderTest.php::testConverterWithMailchimpInput```
