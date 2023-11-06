<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCenter extends Model
{
    use HasFactory;

    protected $table = 'customer_centers';

    protected $fillable = [
        'id',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function folders()
    {
        //get folders with sub folders
        return $this->hasMany(Folder::class)->with('folder_children', 'uploads');
    }
}
