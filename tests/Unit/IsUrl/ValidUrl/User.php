<?php

namespace Tests\Unit\IsUrl\ValidUrl;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const url = 'url';
    public const social_url = 'social_url';
    public const per_protocol = 'per_protocol';

    #[Describe([
        'cast' => [self::class, 'isUrl']
    ])]
    public ?string $url;

    #[Describe([
        'cast' => [self::class, 'isUrl'],
        'protocols' => ['http']
    ])]
    public ?string $social_url;

    #[Describe([
        'cast' => [self::class, 'isUrl'],
        'protocols' => ['udp']
    ])]
    public ?string $per_protocol;
}