<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Image as AppImage;

class ImageService
{
    public function uploadAndResize(Request $request){
        $image = $request->file('image');
        $time = time();
        $imageName = $time.'.'.$image->extension();
        $img = Image::make($image->path());

        $thumbnail = $this->createThumbnail($img, $imageName, $request);
        $thumbnail->save();

        $webOptimized = $this->createWebOptimized($image, $time, $request);
        $webOptimized->save();

        $original = $this->saveOriginal($image, $imageName, $request);
        $original->save();

        return $time;
    }

    private function createThumbnail($img, $imageName, $request){
        $thumbnail = new AppImage();
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        });
        $thumbnail->title = $request->title;
        $thumbnail->type = "Thumbnail";
        Storage::disk('s3')->put('images/thumbnail/'.$imageName, $img->stream(), 'public');
        $thumbnail->path = 'images/thumbnail/'.$imageName;
        $thumbnail->size =  Storage::disk('s3')->size( $thumbnail->path);
        return $thumbnail;
    }

    private function createWebOptimized($image, $time, $request){
        $webOptimized = new AppImage();
        $imageName = $time.'.jpg';
        $jpg = Image::make($image->path())->encode('jpg', 90)
            ->resize(500,500, function ($constraint) {
                $constraint->aspectRatio();
            }
        );
        $webOptimized->title = $request->title;
        $webOptimized->type = "Web Optimized";
        Storage::disk('s3')->put('images/'.$imageName, $jpg->stream(), 'public');
        $webOptimized->path = 'images/'.$imageName;
        $webOptimized->size =  Storage::disk('s3')->size( $webOptimized->path);
        return $webOptimized;
    }

    private function saveOriginal($image, $imageName, $request){
        $img = Image::make($image->path());
        $original = new AppImage();
        $original->title = $request->title;
        $original->type = "Original";
        Storage::disk('s3')->put('images/original/'.$imageName, $img->stream(), 'public');
        $original->path = 'images/original/'.$imageName;
        $original->size =  Storage::disk('s3')->size( $original->path);
        return $original;
    }
}
