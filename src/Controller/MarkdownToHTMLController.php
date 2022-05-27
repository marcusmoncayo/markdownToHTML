<?php

  namespace App\Controller;

  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use App\lib\MarkdownToHTMLBuilder;

class MarkdownToHTMLController
{
    public function convertMarkdownToHTML(Request $request): Response
    {
        $decodedMarkdown = $request->query->get('markdown');

        if (!isset($decodedMarkdown)) {
            return new Response("<p>Please use param \"markdown\" with valid input.</p>");
        }

        $markdownToHTMLBuilder = new MarkdownToHTMLBuilder();

        try {
            $result = $markdownToHTMLBuilder->build($decodedMarkdown);
        } catch (\Exception $e) {
            return new Response("Please supply valid markdown");
        }

        return new Response($result . "<br/><br/><br/><br/><p>Raw HTML Output</p>" . htmlentities($result));
    }
}
