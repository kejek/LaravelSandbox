<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    /* Fillable */
    protected $fillable = [
        'title', 'path', 'size', 'type'
    ];
    /* @array $appends */
    public $appends = ['url', 'uploaded_time', 'size_in_kb'];

    public function getUrlAttribute(){
        return Storage::disk('s3')->url($this->path);
    }

    public function getUploadedTimeAttribute(){
        return $this->created_at->diffForHumans();
    }

    public function getSizeInKbAttribute(){
        return round($this->size / 1024, 2);
    }
}
