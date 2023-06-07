<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_number',
        'customer_name',
        'customer_address',
        'customer_street',
        'customer_zip',
        'customer_city',
        'customer_country',
        'customer_phone',
        'customer_email',
    ];
}
