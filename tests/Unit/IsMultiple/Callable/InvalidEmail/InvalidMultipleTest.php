<?php

namespace Tests\Unit\IsMultiple\Callable\InvalidEmail;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvalidMultipleTest extends TestCase
{
    #[Test] public function invalid_email(): void
    {
        $this->expectException(NotMultipleException::class);

        User::from([
            User::value => 2,
        ]);
    }
}