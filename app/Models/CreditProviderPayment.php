<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditProviderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_provider_id',
        'adviser',
        'paid_value',
        'description',
        'evidence'
    ];

    public function  creditProvider(){
        return $this->belongsTo(CreditProvider::class);
    }
}
