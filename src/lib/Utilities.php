<?php

namespace App\lib;

class Utilities
{

    const PHP_REGEX_DELIMETER = "/";
    const PHP_REGEX_STARTS_WITH_DELIMETER = "/^";

    public static function getAllTagClasses(): array
    {
        return [HeaderTag::class, AnchorTag::class];
    }
}
