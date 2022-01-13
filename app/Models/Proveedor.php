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
    'document_number',
    'cell_phone1',
    'cell_phone2',
    'address',
    'email',
    'status'
  ];
}
