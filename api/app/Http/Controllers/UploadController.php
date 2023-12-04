<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Upload::all()
        ], 200);
    }

    public function show($id)
    {
        $upload = Upload::find($id);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Upload with id ' . $id . ' not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $upload->toArray()
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            //get uploaded file and extract its properties
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $file->storeAs('public/files', $file_name);
            $url = asset('storage/files/' . $file_name);
            $file_path = $url;
            $file_size = $file->getSize();
            $file_type = $file->getMimeType();

            $file_description = $request->description ? $request->description : null;

            //create new upload object
            $upload = new Upload();
            $upload->file_name = $file_name;
            $upload->file_path = $file_path;
            $upload->file_size = $file_size;
            $upload->file_type = $file_type;
            $upload->description = $file_description;
            $upload->folder_id = $request->folder_id;

            //save upload to database
            $upload->save();

            return response()->json([
                'success' => true,
                'data' => $upload->toArray()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload could not be added',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $upload = Upload::find($id);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Upload with id ' . $id . ' not found'
            ], 400);
        }

        $updated = $upload->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true,
                'data' => $upload->toArray()
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Upload could not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $upload = Upload::find($id);

        if (!$upload) {
            return response()->json([
                'success' => false,
                'message' => 'Upload with id ' . $id . ' not found'
            ], 400);
        }

        if ($upload->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Upload deleted'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Upload could not be deleted'
            ], 500);
        }
    }
}
