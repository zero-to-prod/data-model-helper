<?php

namespace Tests\Unit\MapVia;

use Tests\TestCase;
use Zerotoprod\DataModelHelper\DataModelHelper;

class MapViaTest extends TestCase
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

        self::assertEquals('John Doe', $User->Aliases->items[0]->name);
    }
}