<?php

namespace Tests\Unit\Coerced;

use Tests\TestCase;
use Zerotoprod\DataModelHelper\DataModelHelper;

class CoercedTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModelHelper
     */
    public function from(): void
    {
        $User = User::from([
            'Aliases' => ['name' => 'John Doe'],
        ]);

        self::assertEquals('John Doe', $User->Aliases[0]->name);
    }
}