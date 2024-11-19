<?php

namespace Tests\Unit\IsMultiple\Exception\InvalidUrl;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomExceptionTest extends TestCase
{
    #[Test] public function invalid_email(): void
    {
        $this->expectException(NotMultipleException::class);
        User::from([
            User::value => 2,
        ]);
    }
}