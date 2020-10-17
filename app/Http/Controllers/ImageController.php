<?php

namespace App\Http\Controllers;

use App\Image;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ImageController
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{
    /**
     * @var ImageService
     */
    protected $imageService;

    /**
     * ImageController constructor.
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService){
        $this->imageService = $imageService;
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function index()
    {
        return view('home');
    }

    public function getImages()
    {
        $images = Image::all();
        return view('images')->with('images', $images);
    }


    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return RedirectResponse
     * @throws
     */
    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $this->imageService->uploadAndResize($request);

        return back()
            ->with('success','Image Upload successful');
    }

}
