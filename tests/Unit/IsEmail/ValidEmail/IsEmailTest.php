<?php

namespace Tests\Unit\IsEmail\ValidEmail;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IsEmailTest extends TestCase
{
    #[Test] public function valid_email(): void
    {
        $User = User::from([
            User::email => 'jane@example.com/',
        ]);

        self::assertEquals('jane@example.com/', $User->email);
    }
}