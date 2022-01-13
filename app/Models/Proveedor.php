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
    'tipo_documento',
    'document_number',
    'celular1',
    'celular2',
    'direccion',
    'email',
    'status'
  ];
}
