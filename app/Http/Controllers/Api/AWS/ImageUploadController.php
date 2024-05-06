<?php

namespace App\Http\Controllers\Api\AWS;

use App\Helpers\Response\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function store(Request $request) {
        // store file name in DB
        $file = $request->file('image');
        $name=$file->getClientOriginalName();
        Storage::disk(config('filesystems.cloud'))->put($name, file_get_contents($file));
        return Response::HTTP_OK(['success' => 'Image Uploaded successfully', 'name' => $name]);
    }

    public function read($name) {
        return Response::HTTP_OK(['url' => Storage::disk(config('filesystems.cloud'))->url($name)]);
    }

    public function update($name) {
        //return Response::HTTP_OK(['url', Storage::disk('s3')->url($name)]);
    }

    public function delete($name) {
        Storage::disk(config('filesystems.cloud'))->delete($name);
        return Response::HTTP_OK(['success' => 'Image Removed successfully', 'name' => $name]);
    }
}
