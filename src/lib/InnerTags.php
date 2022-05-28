<?php
  namespace App\lib;

   class InnerTags
   {
    public static function getValidInnerTagsForClass($className): array
    {

        $validInnerTags =  [
            ParagraphTag::class => [AnchorTag::class, StrongTagClass::class],
            AnchorTag::class => [StrongTagClass::class],
            HeaderTag::class => [AnchorTag::class],
            StrongTagClass::class => []
        ];

        return $validInnerTags[$className];
    }
  }