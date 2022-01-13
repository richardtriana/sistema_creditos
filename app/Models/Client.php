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
    'document_number',
    'fecha_nacimiento',
    'email',
    'genero',
    'cell_phone1',
    'cell_phone2',
    'address',
    'estado_civil',
    'lugar_trabajo',
    'cargo',
    'independiente',
    'photo', 'activo'
  ];

  public function credits()
  {
    return $this->hasMany(Credit::class);
  }
}
