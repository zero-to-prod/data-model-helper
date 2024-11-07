<?php

namespace Tests\Unit\IsUrl\Callable\NotString;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IsUrlTest extends TestCase
{
    #[Test] public function invalid_url(): void
    {
        $this->expectException(BadUrlException::class);
        User::from([
            User::url => 1,
        ]);
    }
}