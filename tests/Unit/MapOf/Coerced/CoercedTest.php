<?php

namespace Tests\Unit\MapOf\Coerced;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CoercedTest extends TestCase
{
    #[Test] public function from(): void
    {
        $User = User::from([
            'Aliases' => ['name' => 'John Doe'],
        ]);

        self::assertEquals('John Doe', $User->Aliases[0]->name);
    }
}