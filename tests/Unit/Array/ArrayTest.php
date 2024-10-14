<?php

namespace Tests\Unit\Array;

use Tests\TestCase;
use Zerotoprod\DataModelHelper\DataModelHelper;

class ArrayTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModelHelper
     */
    public function from(): void
    {
        $User = User::from([
            'Aliases' => [
                ['name' => 'John Doe'],
                ['name' => 'John Smith'],
            ]
        ]);

        self::assertEquals('John Doe', $User->Aliases[0]->name);
        self::assertEquals('John Smith', $User->Aliases[1]->name);
    }
}