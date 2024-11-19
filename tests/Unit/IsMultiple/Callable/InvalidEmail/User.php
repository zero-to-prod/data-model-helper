<?php

namespace Tests\Unit\IsMultiple\Callable\InvalidEmail;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const value = 'value';

    #[Describe([
        'cast' => [self::class, 'isMultiple'],
        'of' => 7,
        'on_fail' => [self::class, 'failed'],
    ])]
    public int $value;

    public static function failed(): string
    {
        throw new NotMultipleException();
    }
}