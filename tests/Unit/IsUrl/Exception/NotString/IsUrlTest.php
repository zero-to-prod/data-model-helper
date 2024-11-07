<?php

namespace Tests\Unit\IsUrl\Exception\NotString;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IsUrlTest extends TestCase
{
    #[Test] public function not_string(): void
    {
        $this->expectException(BadUrlException::class);
        User::from([
            User::url => 1,
        ]);
    }
}