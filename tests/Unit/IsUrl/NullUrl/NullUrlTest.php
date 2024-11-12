<?php

namespace Tests\Unit\IsUrl\NullUrl;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NullUrlTest extends TestCase
{
    #[Test] public function valid_url(): void
    {
        $User = User::from(['']);

        self::assertNull($User->url);
    }
}