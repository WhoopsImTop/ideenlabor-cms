<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SiteResource::collection(Site::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // set pageContent json to saveable String
        $pageContent = json_encode($request->pageContent);
        $request->merge(['pageContent' => $pageContent]);

        //if slug is empty set slug to title and replace spaces with dashes and to lowercase
        if ($request->slug == '') {
            $request->merge(['slug' => strtolower(str_replace(' ', '-', $request->title))]);
        } else {
            $request->merge(['slug' => strtolower(str_replace(' ', '-', $request->slug))]);
        }

        return SiteResource::make(Site::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(site $site)
    {
        return SiteResource::make($site);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, site $site)
    {
        // set pageContent json to saveable String
        $pageContent = json_encode($request->pageContent);
        $request->merge(['pageContent' => $pageContent]);
        $site->update($request->all());

        //if slug is empty set slug to title and replace spaces with dashes and to lowercase
        if ($request->slug == '') {
            $request->merge(['slug' => strtolower(str_replace(' ', '-', $request->title))]);
        } else {
            $request->merge(['slug' => strtolower(str_replace(' ', '-', $request->slug))]);
        }

        return SiteResource::make($site);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(site $site)
    {
        return $site->delete();
    }
}
