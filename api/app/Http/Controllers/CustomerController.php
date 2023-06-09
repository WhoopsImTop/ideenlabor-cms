<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function index()
    {
        return CustomerResource::collection(Customer::all());
    }

    public function store(Request $request)
    {
        //generate customer number that auto increments starting from 1000
        $customer_number = 'C' . 10000 + Customer::max('id') + 1;
        $request->merge(['customer_number' => $customer_number]);

        return new CustomerResource(Customer::create($request->all()));
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->all());

        return $customer;
    }

    public function destroy(Customer $customer)
    {
        return $customer->delete();
    }
}
