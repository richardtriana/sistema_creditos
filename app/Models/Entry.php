<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;
    protected $fillable = [
        'headquarter_id',
        'user_id',
        'credit_id',
        'description',
        'date',
        'type_input',
        'price',
    ];
    protected $with = [
        'user'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
