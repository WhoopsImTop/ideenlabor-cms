<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'customer_number' => $this->customer_number,
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'invoice_delivery_date' => $this->invoice_delivery_date,
            'invoice_delivery_start_date' => $this->invoice_delivery_start_date,
            'invoice_delivery_end_date' => $this->invoice_delivery_end_date,
            'invoice_due_date' => $this->invoice_due_date,
            'invoice_title' => $this->invoice_title,
            'invoice_start_text' => $this->invoice_start_text,
            'invoice_positions' => $this->invoice_positions,
            'invoice_payment_condition' => $this->invoice_payment_condition,
            'invoice_afterword' => $this->invoice_afterword,
            'invoice_path' => $this->invoice_path,
            'invoice_total' => $this->invoice_total,
            'invoice_status' => $this->invoice_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => $this->customer,
        ];
    }
}
