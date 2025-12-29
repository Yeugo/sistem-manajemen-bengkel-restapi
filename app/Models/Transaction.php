<?php

namespace App\Models;

use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'reference_no',
        'labor_cost',
        'total_price',
        'notes'
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
