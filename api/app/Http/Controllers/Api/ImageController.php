<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function show(Image $image): Response
    {
        return response($image->image_data, 200)->header('Content-Type', $image->mime_type)->header('Cache-Control', 'private, max-age=86400');
    }
}
