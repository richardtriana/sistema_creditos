<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'headquarter_id',
        'user_id',
        'description',
        'date',
        'type_output',
        'price',
        'status'
    ];
}
