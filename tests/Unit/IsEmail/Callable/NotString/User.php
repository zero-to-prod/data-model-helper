<?php

namespace Tests\Unit\IsEmail\Callable\NotString;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const email = 'email';

    #[Describe([
        'cast' => [self::class, 'isEmail'],
        'on_fail' => [self::class, 'failed'],
    ])]
    public string $email;

    public static function failed(): string
    {
        throw new BadEmailException();
    }
}