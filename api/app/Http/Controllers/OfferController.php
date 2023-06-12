<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Offer::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Offer::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        return $offer;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        $offer->update($request->all());

        return $offer;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        return $offer->delete();
    }
}
