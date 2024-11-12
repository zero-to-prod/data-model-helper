<?php

namespace Tests\Unit\IsEmail\NullUrl;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NullUrlTest extends TestCase
{
    #[Test] public function null_email(): void
    {
        $User = User::from();

        self::assertNull($User->email);
    }
}