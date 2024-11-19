<?php

namespace Tests\Unit\When\Eval;

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
        'eval' => '$value >= 10',
        'true' => [self::class, 'true'],
        'false' => [self::class, 'false'],
    ])]
    public int $value;

    public static function true(): int
    {
        return 1;
    }
    public static function false(): int
    {
        return 0;
    }
}