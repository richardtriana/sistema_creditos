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
        'phone',
        'pos_printer'
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
