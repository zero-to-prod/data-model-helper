<?php

namespace Tests\Unit\IsEmail\Required;

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
        'required'
    ])]
    public string $email;

    #[Describe([
        'cast' => [self::class, 'isEmail'],
        'required'
    ])]
    public string $social_email;
}