<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return site::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return site::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(site $site)
    {
        return $site;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, site $site)
    {
        $site->update($request->all());

        return $site;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(site $site)
    {
        return $site->delete();
    }
}
