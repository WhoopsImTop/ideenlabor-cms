<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_number',
        'customer_company_name',
        'customer_pin',
        'customer_name',
        'customer_address',
        'customer_street',
        'customer_zip',
        'customer_city',
        'customer_country',
        'customer_phone',
        'customer_email',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_number', 'customer_number');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'customer_number', 'customer_number');
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class);
    }

    public function customer_center()
    {
        return $this->hasOne(CustomerCenter::class)->with('uploads');
    }
}
