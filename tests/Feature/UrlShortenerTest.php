<?php

namespace Tests\Feature;

use App\ShortUrl;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UrlShortenerTest extends TestCase
{
    public function testBasicWebRoutes()
    {
        //get without shortcode returns 'not found'
        $this->get('/visit/')->assertNotFound();
        //get with bad shortcode returns 'not found'
        $this->get('/visit/notarealshortcode')->assertNotFound();
    }
    
    public function testBasicApiRoutes()
    {
        //post without data redirects back
        $this->post('/api/create')->assertStatus(302);
        //delete without shortcode returns 'not found'
        $this->delete('/api/delete')->assertNotFound();
    }
    
    public function testShortCodeCreation()
    {
        //http://host.domain.ext/path/to?query=1#anchor1
        //invalid urls redirect back
        $this->post('/api/create', ['destination'=> '/just/a/path'])->assertStatus(302);
        $this->post('/api/create', ['destination'=> 'www.noprotocol.com'])->assertStatus(302);
        $this->post('/api/create', ['destination'=> 'www.noextension'])->assertStatus(302);
        $this->post('/api/create', ['destination'=> 123])->assertStatus(302);
        $this->post('/api/create', ['destination'=> 'not even a url'])->assertStatus(302);
        $this->post('/api/create', ['destination'=> null])->assertStatus(302);
        $this->post('/api/create', ['destination'=> []])->assertStatus(302);
        $this->post('/api/create', ['destination'=> true])->assertStatus(302);
        //way too long url redirects back
        $this->post('/api/create', ['destination'=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed id libero nec arcu rutrum mattis sed ut justo. Nunc congue interdum mi. Nullam quis lorem tempus, convallis lectus ut, bibendum massa. Praesent venenatis tellus mauris, eu sagittis arcu eleifend non. Maecenas blandit dignissim eros ut accumsan. Vestibulum ultrices ante ac orci dignissim, vulputate aliquet mi bibendum. Pellentesque ligula sapien, vulputate id diam at, vehicula tincidunt lectus. Nulla egestas velit in tristique pellentesque. Mauris in mauris nunc. Aenean non metus non libero eleifend luctus eget eu urna. Nulla facilisi. Aenean eget dictum nisl. Suspendisse vel sapien ut lacus volutpat sagittis sed a ex. Maecenas tellus augue, pretium rutrum velit vel, ullamcorper malesuada turpis. Aenean luctus facilisis erat quis vestibulum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc finibus convallis eros, non dapibus eros. Duis sit amet fringilla nisl. Morbi eu vestibulum lacus. Donec nec risus quis metus euismod consectetur a vulputate augue. Integer laoreet pulvinar ante, ac euismod ante viverra at. Suspendisse potenti. Maecenas dictum facilisis mi, eget dapibus nibh ullamcorper eu. Cras in pharetra enim. Ut eu neque tincidunt velit hendrerit pretium malesuada et arcu. Maecenas ac fermentum diam, et pharetra augue. Nunc pulvinar, ipsum at elementum ornare, ante ipsum dictum neque, sed facilisis est justo ut magna. Integer quis volutpat felis, eget lacinia mauris. Donec leo nibh, rutrum quis dolor ut, imperdiet malesuada urna. Sed finibus lacus suscipit augue blandit, vitae fermentum quam dapibus. Nunc sit amet iaculis est. Sed vel orci tellus. Vestibulum sit amet tincidunt nunc, ut accumsan lectus. Etiam finibus feugiat magna nec tempor. Pellentesque ullamcorper quam quis dolor rhoncus dapibus. Phasellus gravida auctor varius. Integer eget volutpat lacus. Nunc vehicula sapien sed diam maximus venenatis. Curabitur in aliquam turpis, at consectetur odio. Cras erat lectus, ornare vel sagittis turpis duis.'])->assertStatus(302);
        // 2048 chars exactly
        $valid_lorem_imsum = 'http://lorem.ipsum/dolor/sit/amet/consectetur/adipiscing/elit/Sed/id/libero/nec/arcu/rutrum/mattis/sed/ut/justo/Nunc/congue/interdum/mi/Nullam/quis/lorem/tempus/convallis/lectus/ut/bibendum/massa/Praesent/venenatis/tellus/mauris/eu/sagittis/arcu/eleifend/non/Maecenas/blandit/dignissim/eros/ut/accumsan/Vestibulum/ultrices/ante/ac/orci/dignissim/vulputate/aliquet/mi/bibendum/Pellentesque/ligula/sapien/vulputate/id/diam/at/vehicula/tincidunt/lectus/Nulla/egestas/velit/in/tristique/pellentesque/Mauris/in/mauris/nunc/Aenean/non/metus/non/libero/eleifend/luctus/eget/eu/urna/Nulla/facilisi/Aenean/eget/dictum/nisl/Suspendisse/vel/sapien/ut/lacus/volutpat/sagittis/sed/a/ex/Maecenas/tellus/augue/pretium/rutrum/velit/vel/ullamcorper/malesuada/turpis/Aenean/luctus/facilisis/erat/quis/vestibulum/Class/aptent/taciti/sociosqu/ad/litora/torquent/per/conubia/nostra/per/inceptos/himenaeos/Nunc/finibus/convallis/eros/non/dapibus/eros/Duis/sit/amet/fringilla/nisl/Morbi/eu/vestibulum/lacus/Donec/nec/risus/quis/metus/euismod/consectetur/a/vulputate/augue/Integer/laoreet/pulvinar/ante/ac/euismod/ante/viverra/at/Suspendisse/potenti/Maecenas/dictum/facilisis/mi/eget/dapibus/nibh/ullamcorper/eu/Cras/in/pharetra/enim/Ut/eu/neque/tincidunt/velit/hendrerit/pretium/malesuada/et/arcu/Maecenas/ac/fermentum/diam/et/pharetra/augue/Nunc/pulvinar/ipsum/at/elementum/ornare/ante/ipsum/dictum/neque/sed/facilisis/est/justo/ut/magna/Integer/quis/volutpat/felis/eget/lacinia/mauris/Donec/leo/nibh/rutrum/quis/dolor/ut/imperdiet/malesuada/urna/Sed/finibus/lacus/suscipit/augue/blandit/vitae/fermentum/quam/dapibus/Nunc/sit/amet/iaculis/est/Sed/vel/orci/tellus/Vestibulum/sit/amet/tincidunt/nunc/ut/accumsan/lectus/Etiam/finibus/feugiat/magna/nec/tempor/Pellentesque/ullamcorper/quam/quis/dolor/rhoncus/dapibus/Phasellus/gravida/auctor/varius/Integer/eget/volutpat/lacus/Nunc/vehicula/sapien/sed/diam/maximus/venenatis/Curabitur/in/aliquam/turpis/at/consectetur/odio/Cras/erat/lectus/ornare/vel/sagittis/turpis/duis/litora/torquent/per/conubia/nostra/per/inceptos/hime';
        $this->post('/api/create', ['destination'=> $valid_lorem_imsum])->assertStatus(201);
        //way too long url redirects back even if "valid" (2049 chars)
        $this->post('/api/create', ['destination'=> $valid_lorem_imsum.'a'])->assertStatus(302);
    
        //valid url
        $response = $this->post('/api/create', ['destination'=> 'https://google.com']);
        //returns 'created'
        $response->assertStatus(201);
        //returns shortcode
        $shortcode = $response->getContent();
        $this->assertIsString($shortcode);
        //actually exists
        $this->assertTrue(ShortUrl::ofCode($shortcode)->exists);
        
    }
    
    public function testShortCodeDeletion()
    {
        $shortcode = $this->post('/api/create', ['destination'=> 'https://google.com'])->getContent();
        //ensure creation was successful
        $this->assertTrue(ShortUrl::ofCode($shortcode)->exists);
        
        //deleting shortcode returns 'no content'
        $this->delete("/api/delete/{$shortcode}")->assertStatus(204);
        //ensure deletion was successful
        $this->assertNull(ShortUrl::ofCode($shortcode));
        //looking for deleted shortcode returns 'not found'
        $this->get("/visit/{$shortcode}")->assertNotFound();
        
        //deleting fake shortcode returns 'no content'
        $this->delete('/api/delete/notarealshortcode')->assertStatus(204);
        //deleting fake-ish shortcode returns 'no content'
        $this->delete('/api/delete/'.$this->getFakeShortcode())->assertStatus(204);
        
        //
    }

    public function testShortCodeVisiting()
    {
        $shortcode = $this->post('/api/create', ['destination'=> 'https://google.com'])->getContent();
        
        //existing shortcode redirects to destination
        $response = $this->get("/visit/{$shortcode}");
        $response->assertStatus(302);
        $response->assertRedirect('https://google.com');
        
        //fake-ish shortcode returns 'not found'
        $response = $this->get('/visit/'.$this->getFakeShortcode())->assertNotFound();
    }
    
    protected function getFakeShortcode()
    {
        //get max id
        $short_url = ShortUrl::orderBy('id', 'desc')->first();
        //iterate id by one so that we have an id that could be real, but isn't
        $short_url->id++;
        //find the shortcode of fake-ish id
        $fakeish_code = $short_url->getCodeAttribute();
        //and give it back to the test
        return $fakeish_code;
    }
}
