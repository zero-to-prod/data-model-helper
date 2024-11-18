<?php

namespace Tests\Unit\PregMatch;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class UserRequired
{
    use DataModel;
    use DataModelHelper;

    public const name = 'name';

    #[Describe([
        'cast' => [self::class, 'pregMatch'],
        'pattern' => '/s/',
        'required'
    ])]
    public array $name;
}