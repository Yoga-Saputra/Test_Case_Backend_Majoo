<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'Transactions';

    protected $fillable = [
        'merchant_id', 'outlet_id', 'bill_total', 'created_by', 'updated_by'
    ];
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
