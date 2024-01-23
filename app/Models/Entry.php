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

    protected $dates = [
        'date' => 'date:Y-m-d'
    ];
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credit_id');
    }
    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }
}
