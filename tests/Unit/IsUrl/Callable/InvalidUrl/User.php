<?php

namespace Tests\Unit\IsUrl\Callable\InvalidUrl;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const url = 'url';

    #[Describe([
        'cast' => [self::class, 'isUrl'],
        'on_fail' => [self::class, 'failed'],
    ])]
    public string $url;

    public static function failed(): string
    {
        throw new BadUrlException();
    }
}