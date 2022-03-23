<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
  use HasFactory;

  protected $table = 'providers';

  protected $fillable = [
    'business_name',
    'type_document',
    'document',
    'phone_1',
    'phone_2',
    'address',
    'email',
    'status'
  ];
}
