<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'Merchants';

    protected $fillable = [
        'user_id', 'merchant_name', 'created_by', 'updated_by'
    ];
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
