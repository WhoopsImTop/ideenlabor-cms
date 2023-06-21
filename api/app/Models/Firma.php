<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firma extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_zip',
        'company_city',
        'company_country',
        'company_phone',
        'company_email',
        'company_website',
        'company_image',
        'company_bank_holder',
        'company_bank_name',
        'company_bank_iban',
        'company_bank_bic',
        'company_tax_id',
        'company_vat_id'
    ];
}
