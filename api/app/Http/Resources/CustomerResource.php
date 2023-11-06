<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'applications' => $this->applications,
            'customer_number' => $this->customer_number,
            'customer_company_name' => $this->customer_company_name ?? '',
            'customer_name' => $this->customer_name,
            'customer_address' => $this->customer_address ?? '',
            'customer_street' => $this->customer_street,
            'customer_zip' => $this->customer_zip,
            'customer_city' => $this->customer_city,
            'customer_country' => $this->customer_country,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'customer_center' => $this->customer_center,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'invoices' => $this->invoices,
            'offers' => $this->offers,
        ];
    }
}
