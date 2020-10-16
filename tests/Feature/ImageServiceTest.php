<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function testImageUploadPost(){

        $data = [
            "name"=>"file.jpg",
            "file"=>"file"
        ];

        $response = $this->post('/api/upload_image', $data);

        $response->assertStatus(200);
    }
}
