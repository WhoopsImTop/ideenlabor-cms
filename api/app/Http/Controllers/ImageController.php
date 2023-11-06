<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\ImageResource;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all images
        $images = Image::all();
        return ImageResource::collection($images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageRequest $request)
    {
        // Validiere den Request, um sicherzustellen, dass ein Bild hochgeladen wurde
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maximale Dateigröße: 2MB
        ]);

        // Speichere das hochgeladene Bild im public/images-Verzeichnis
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        //store image name in database
        $image = Image::create([
            'src' => $imageName,
            'title' => $request->title
        ]);

        return ImageResource::make($image);
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //get specific image
        $image = Image::find($image->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageRequest $request, Image $image)
    {
        // Validiere den Request, um sicherzustellen, dass ein Bild hochgeladen wurde
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maximale Dateigröße: 2MB
        ]);

        // Speichere das hochgeladene Bild im public/images-Verzeichnis
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        //store image name in database
        $image->update([
            'src' => $imageName,
            'title' => $request->title
        ]);

        return ImageResource::make($image);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //delete image
        $image->delete();
    }
}
