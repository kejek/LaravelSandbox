<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $imageService;

    public function __construct(ImageService $imageService){
        $this->imageService = $imageService;
    }

    public function uploadImage(){
        $upload = $this->imageService->uploadImage();

        return ["upload" => $upload];
    }
}
