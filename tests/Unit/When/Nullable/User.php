<?php

namespace Tests\Unit\When\Nullable;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const value = 'value';

    #[Describe([
        'cast' => [self::class, 'when'],
    ])]
    public ?int $value;

    public static function false(): int
    {
        return 0;
    }
}