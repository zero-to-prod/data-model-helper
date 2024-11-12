<?php

namespace Tests\Unit\IsEmail\Exception\InvalidUrl;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomExceptionTest extends TestCase
{
    #[Test] public function invalid_email(): void
    {
        $this->expectException(BadEmailException::class);
        User::from([
            User::email => 'invalid email',
        ]);
    }
}