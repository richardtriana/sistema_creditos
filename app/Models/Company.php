<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'legal_representative',
        'nit',
        'address',
        'email',
        'telephone',
        'mobile',
        'logo',
        'condition_order',
        'condition_quotation',
        'whatsapp_msg',
        'method'
    ];
}
