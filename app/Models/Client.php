<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'last_name',
    'type_document',
    'document',
    'profession',
    'birth_date',
    'email',
    'gender',
    'phone_1',
    'phone_2',
    'address',
    'civil_status',
    'workplace',
    'occupation',
    'independent',
    'photo',
    'status'
  ];

  public function credits()
  {
    return $this->hasMany(Credit::class);
  }

  public function headquarter()
  {
      return $this->belongsTo(Headquarter::class, 'headquarter_id');
  }
}
