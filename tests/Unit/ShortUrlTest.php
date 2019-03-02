<?php

namespace Tests\Unit;

use App\ShortUrl;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortUrlTest extends TestCase
{
    public function testShortCodeCreation()
    {
        $short_url = ShortUrl::create(['destination' => 'https://example.com']);
        //ensure creation was successful
        $this->assertTrue($short_url->exists);
        
        //ensure shortcode generates properly
        $this->assertIsString($short_url->code);
    }
    public function testCodeAttribute()
    {
        $short_url = (new ShortUrl());
        $short_url->id = 1;
        //code should not have '+/'
        $this->assertFalse(strpos($short_url->code, '+/'));
        //code should look like 'MQ'
        $this->assertSame('MQ', $short_url->code);
        
        //code should not have '=' or '=='
        $this->assertStringEndsNotWith('=', $short_url->code, 'Attribute failed to trim base64 padding');
        $short_url->id = 12; //'MTI='
        $this->assertStringEndsNotWith('=', $short_url->code, 'Attribute failed to trim base64 padding');
        $short_url->id = 123; //'MTIz'
        $this->assertStringEndsNotWith('=', $short_url->code, 'Attribute failed to trim base64 padding');
        $short_url->id = 1234; //'MTIzNA=='
        $this->assertStringEndsNotWith('=', $short_url->code, 'Attribute failed to trim base64 padding');
    }
}
