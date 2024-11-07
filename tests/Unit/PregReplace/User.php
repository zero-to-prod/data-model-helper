<?php

namespace Tests\Unit\PregReplace;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const name = 'name';
    public const lastname = 'lastname';

    #[Describe([
        'cast' => [self::class, 'pregReplace'],
        'pattern' => '/[^\x00-\x7F]/'
    ])]
    public ?string $name;

    #[Describe([
        'cast' => [self::class, 'pregReplace'],
        'pattern' => '/s/',
        'replacement' => '1'
    ])]
    public string $lastname;
}