<?php

namespace Tests\Unit\PregReplace;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class UserRequired
{
    use DataModel;
    use DataModelHelper;

    public const name = 'name';

    #[Describe([
        'cast' => [self::class, 'pregReplace'],
        'pattern' => '/[^\x00-\x7F]/',
        'required'
    ])]
    public string $name;
}