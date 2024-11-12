<?php

namespace Tests\Unit\IsEmail\ValidUrl;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const email = 'email';

    #[Describe([
        'cast' => [self::class, 'isEmail']
    ])]
    public ?string $email;
}