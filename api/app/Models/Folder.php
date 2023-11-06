<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folders';

    protected $fillable = [
        'description',
        'folder_name',
        'folder_parent_id',
        'customer_id',
        'folder_hash',
        'sub_folders'
    ];

    public function generateHash()
    {
        //generate random integer and letter and hash it
        $this->folder_hash = md5(rand(0, 100000) . $this->folder_name . $this->description . $this->customer_id);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function folder_parent()
    {
        return $this->belongsTo(Folder::class);
    }

    public function folder_children()
    {
        return $this->hasMany(Folder::class, 'folder_parent_id', 'id');
    }

    public function customer_center()
    {
        return $this->belongsTo(CustomerCenter::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
