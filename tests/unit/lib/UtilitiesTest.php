<?php

namespace unit\lib;

use App\lib\AnchorTag;
use App\lib\HeaderTag;
use App\lib\ParagraphTag;
use App\lib\Utilities;
use PHPUnit\Framework\TestCase;

final class UtilitiesTest extends TestCase
{
    public function testGetAllTagClasses(): void
    {

        $startTags = Utilities::getAllTagClasses();
        $expected = [HeaderTag::class, AnchorTag::class];

        $this->assertEquals($expected, $startTags);
    }
}
