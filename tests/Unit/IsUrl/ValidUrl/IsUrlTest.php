<?php

namespace Tests\Unit\IsUrl\ValidUrl;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IsUrlTest extends TestCase
{
    #[Test] public function valid_url(): void
    {
        $User = User::from([
            User::url => 'https://example.com/',
        ]);

        self::assertEquals('https://example.com/', $User->url);
    }

    #[Test] public function valid_url_with_protocol(): void
    {
        $User = User::from([
            User::url => 'https://example.com/',
            User::social_url => 'https://example.com/',
        ]);

        self::assertEquals('https://example.com/', $User->url);
        self::assertEquals('https://example.com/', $User->social_url);
    }

    #[Test] public function per_protocol(): void
    {
        $User = User::from([
            User::per_protocol => 'invalid',
        ]);

        self::assertEquals('invalid', $User->per_protocol);
    }
}