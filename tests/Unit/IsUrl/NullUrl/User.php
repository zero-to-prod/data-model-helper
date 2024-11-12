<?php

namespace Tests\Unit\IsUrl\NullUrl;

use Zerotoprod\DataModel\DataModel;
use Zerotoprod\DataModel\Describe;
use Zerotoprod\DataModelHelper\DataModelHelper;

class User
{
    use DataModel;
    use DataModelHelper;

    public const url = 'url';

    #[Describe([
        'cast' => [self::class, 'isUrl']
    ])]
    public ?string $url;
}