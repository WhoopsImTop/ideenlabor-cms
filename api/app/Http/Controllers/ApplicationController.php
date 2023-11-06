<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return Application::all();
    }

    public function show(Application $application)
    {
        return $application;
    }

    public function store(Request $request)
    {
        return Application::create($request->all());
    }

    public function update(Request $request, Application $application)
    {
        $application->update($request->all());

        return $application;
    }

    public function destroy(Application $application)
    {
        return $application->delete();
    }
}
