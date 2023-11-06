<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FolderController extends Controller
{
    public function index()
    {
        try {
            //query all folders and return it as json
            $folders = Folder::all();

            return response()->json([
                'success' => true,
                'data' => $folders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($folder_hash)
    {
        try {
            //get Folder and query all uploads and folder
            $folder = Folder::with('uploads', 'folder_children')->where('folder_hash', $folder_hash)->first();

            return response()->json([
                'success' => true,
                'data' => $folder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            //create new folder
            $folder = new Folder();
            $folder->folder_name = $request->folder_name;
            $folder->description = $request->description;
            
            $folder->customer_id = $request->customer_id;
            $folder->generateHash();
            $folder->folder_parent_id = $request->folder_parent_id;
            $folder->save();

            return response()->json([
                'success' => true,
                'data' => $folder,
                'message' => 'Folder created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder creation failed'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            //get folder and update it
            $folder = Folder::where('customer_id', $request->customer_id)->where('folder_hash', $request->folder_hash)->first();
            $folder->folder_name = $request->folder_name;
            $folder->description = $request->description;
            $folder->save();

            return response()->json([
                'success' => true,
                'data' => $folder,
                'message' => 'Folder updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder update failed'
            ], 500);
        }
    }

    public function destroy($customer_id, $folder_hash)
    {
        try {
            //get folder and delete it
            $folder = Folder::where('customer_id', $customer_id)->where('folder_hash', $folder_hash)->first();
            $folder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Folder deletion failed'
            ], 500);
        }
    }
}
