<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $table = 'Outlets';

    protected $fillable = [
        'merchant_id', 'outlet_name', 'created_by', 'updated_by'
    ];
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
