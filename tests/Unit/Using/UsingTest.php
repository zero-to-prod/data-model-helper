<?php

namespace Tests\Unit\Using;

use Tests\TestCase;
use Zerotoprod\DataModelHelper\DataModelHelper;

class UsingTest extends TestCase
{
    /**
     * @test
     *
     * @see DataModelHelper
     */
    public function from(): void
    {
        $User = User::from([
            'Aliases' => [['name' => 'John Doe']],
        ]);

        self::assertEquals('John Doe', $User->Aliases->items[0]['name']);
    }
}