<?php

namespace Tests\Unit\IsEmail\Exception\NotString;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotStringEmailCustomExceptionTest extends TestCase
{
    #[Test] public function not_string(): void
    {
        $this->expectException(BadEmailException::class);
        User::from([
            User::email => 1,
        ]);
    }
}