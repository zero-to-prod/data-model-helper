<?php

namespace Tests\Unit\IsEmail\Callable\InvalidEmail;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvalidEmailTest extends TestCase
{
    #[Test] public function invalid_email(): void
    {
        $this->expectException(BadEmailException::class);
        User::from([
            User::email => 'invalid email',
        ]);
    }
}