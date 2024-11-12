<?php

namespace Tests\Unit\PregMatch;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const name = 'name';
    public const s = 's';
    public const as_null = 'as_null';
    public const offset = 'offset';

    #[Describe([
        'cast' => [self::class, 'pregMatch'],
        'pattern' => '/s/',
    ])]
    public ?array $name;

    #[Describe([
        'cast' => [self::class, 'pregMatch'],
        'pattern' => '/s/',
        'match_on' => 0
    ])]
    public ?string $s;

    #[Describe([
        'cast' => [self::class, 'pregMatch'],
        'pattern' => '/s/',
        'match_on' => 0,
        'flags' => PREG_UNMATCHED_AS_NULL
    ])]
    public ?string $as_null;

    #[Describe([
        'cast' => [self::class, 'pregMatch'],
        'pattern' => '/s/',
        'offset' => 1
    ])]
    public ?array $offset;
}