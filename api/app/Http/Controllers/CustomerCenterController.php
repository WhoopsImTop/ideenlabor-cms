<?php

namespace App\Http\Controllers;

use App\Models\CustomerCenter;
use Illuminate\Http\Request;
use App\Models\Folder;

class CustomerCenterController extends Controller
{
    public function index()
    {
        $customerCenter = CustomerCenter::all();

        foreach ($customerCenter as $customer_center) {
            $customer_center->customer;
            $customer_center->folders;
        }
        return response()->json([
            'success' => true,
            'data' => $customerCenter
        ], 200);
    }

    public function show($id)
    {
        $customer_center = CustomerCenter::find($id);
        $customer_center->customer;
        $customer_center->folders;

        if (!$customer_center) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Center with id ' . $id . ' not found'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $customer_center->toArray()
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'customer_id' => 'required',
            ]);

            $customer_center = new CustomerCenter();
            $customer_center->customer_id = $request->customer_id;

            //find customer and generate random pin
            $customer = $customer_center->customer;
            if (!$customer->customer_pin) {
                $pin = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
                $customer->customer_pin = $pin;
                $customer->save();
            }
            //save customer center
            $customer_center->save();

            //generate default Folder related with customerCenter
            $folder = new Folder();
            $folder->folder_name = 'root';
            $folder->description = 'root folder';
            $folder->customer_id = $customer_center->customer_id;
            $folder->customer_center()->associate($customer_center->id);
            $folder->generateHash();
            //relate to customer center
            $folder->save();

            return response()->json([
                'success' => true,
                'data' => $customer_center->toArray()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Customer Center creation failed'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $customer_center = CustomerCenter::find($id);

        if (!$customer_center) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Center with id ' . $id . ' not found'
            ], 400);
        }

        $updated = $customer_center->fill($request->all())->save();

        if ($updated)
            return response()->json([
                'success' => true,
                'data' => $customer_center->toArray()
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Customer Center could not be updated'
            ], 500);
    }

    public function destroy($id)
    {
        $customer_center = CustomerCenter::find($id);

        if (!$customer_center) {
            return response()->json([
                'success' => false,
                'message' => 'Customer Center with id ' . $id . ' not found'
            ], 400);
        }

        if ($customer_center->delete())
            return response()->json([
                'success' => true,
                'message' => 'Customer Center deleted'
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Customer Center could not be deleted'
            ], 500);
    }
}
