<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
  use HasFactory;

  protected $table = 'proveedores';

  protected $fillable = [
    'name',
    'last_name',
    'type_document',
    'document',
    'phone_1',
    'phone_2',
    'address',
    'email',
    'status'
  ];
}
