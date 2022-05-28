<?php
  namespace App\lib;

   class InnerTags
   {
    public static function getValidInnerTagsForClass($className): array
    {

        $validInnerTags =  [
            ParagraphTag::class => [AnchorTag::class],
            AnchorTag::class => [],
            HeaderTag::class => [AnchorTag::class]
        ];

        return $validInnerTags[$className];
    }
  }