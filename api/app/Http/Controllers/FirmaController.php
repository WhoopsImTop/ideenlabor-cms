<?php

namespace App\Http\Controllers;

use App\Models\Firma;
use App\Http\Requests\StoreFirmaRequest;
use App\Http\Requests\UpdateFirmaRequest;
use App\Models\Image;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //show all firms
        $firms = Firma::all();
        return $firms;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFirmaRequest $request)
    {
        //save firm to database
        //check if request has company_image as file and create image
        if ($request->hasFile('company_image')) {
            //save image to public/images
            $path = $request->file('company_image')->store('public/images');
            //get the file name
            $fileName = basename($path);
            //create the image
            $image = Image::create([
                'title' => $request->company_name,
                'src' => $fileName
            ]);
            //set company_image to image id
            $request->merge(['company_image' => $image->id]);
        }
        $firm = Firma::create($request->all());

        return $firm;
    }

    /**
     * Display the specified resource.
     */
    public function show(Firma $firma)
    {
        //get specific firm
        $firm = Firma::find($firma->id);
        return $firm;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFirmaRequest $request)
    {
        //always update the first company
        $firm = Firma::find($request->id);
        //check if request has company_image as file and create image

        $firm->update($request->all());
        return $firm;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Firma $firma)
    {
        //delete firm
        $firma->delete();
    }
}
