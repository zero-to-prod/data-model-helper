<?php

namespace Tests\Unit\IsUrl\Required;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const url = 'url';

    #[Describe([
        'cast' => [self::class, 'isUrl'],
        'required'
    ])]
    public string $url;

    #[Describe([
        'cast' => [self::class, 'isUrl'],
        'required'
    ])]
    public string $social_url;
}