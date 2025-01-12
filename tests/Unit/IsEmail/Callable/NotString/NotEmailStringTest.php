<?php

namespace Tests\Unit\IsEmail\Callable\NotString;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotEmailStringTest extends TestCase
{
    #[Test] public function invalid_email(): void
    {
        $this->expectException(BadEmailException::class);
        User::from([
            User::email => 1,
        ]);
    }
}