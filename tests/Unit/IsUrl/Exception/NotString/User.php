<?php

namespace Tests\Unit\IsUrl\Exception\NotString;

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
        'exception' => BadUrlException::class
    ])]
    public string $url;
}