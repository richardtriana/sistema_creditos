<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Headquarter extends Model
{
    use HasFactory;
    protected $fillable = [
        'headquarter',
        'status',
        'address',
        'nit',
        'email',
        'legal_representative',
        'cell_phone',
        'pos_printer'
    ];
}
