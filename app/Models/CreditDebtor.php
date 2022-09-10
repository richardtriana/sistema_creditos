<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDebtor extends Model
{
    use HasFactory;

    protected $table = 'credit_debtor';

    protected $fillable = [
        'credit_id',
        'debtor_id'
    ];
}
