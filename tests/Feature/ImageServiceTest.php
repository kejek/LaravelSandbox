<?php

namespace Tests\Feature;

use App\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    public function testImageUploadPost(){
        $data = [
            "title" => "Test",
            "image" => UploadedFile::fake()->image('myImage.jpg')
        ];

        $response = $this->post('/upload', $data);

        //should get redirected back to home
        $response->assertStatus(302);
    }

    public function testImageIsSavedInDatabaseAsOriginal(){
        $data = [
            "title" => "Test",
            "image" => UploadedFile::fake()->image('myImage.jpg')
        ];

        $this->post('/upload', $data);

        $this->assertDatabaseHas('images', [
            'type' => 'Original',
            'title' => 'Test'
        ]);

    }

    public function testImageIsSavedInDatabaseAsThumbnail(){
        $data = [
            "title" => "Test",
            "image" => UploadedFile::fake()->image('myImage.jpg')
        ];

        $this->post('/upload', $data);

        $this->assertDatabaseHas('images', [
            'type' => 'Thumbnail',
            'title' => 'Test'
        ]);

    }

    public function testImageIsSavedInDatabaseAsOptimized(){
        $data = [
            "title" => "Test",
            "image" => UploadedFile::fake()->image('myImage.jpg')
        ];

        $this->post('/upload', $data);

        $this->assertDatabaseHas('images', [
            'type' => 'Web Optimized',
            'title' => 'Test'
        ]);

    }
}
