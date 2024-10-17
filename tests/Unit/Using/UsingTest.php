<?php

namespace Tests\Unit\Using;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UsingTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'Aliases' => [['name' => 'John Doe']],
        ]);

        self::assertEquals('John Doe', $User->Aliases->items[0]['name']);
    }
}