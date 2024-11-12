<?php

namespace Tests\Unit\IsEmail\Exception\NotString;

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
        'exception' => BadEmailException::class
    ])]
    public string $email;
}