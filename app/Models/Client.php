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
    'tipo_documento',
    'document_number',
    'fecha_nacimiento',
    'email',
    'genero',
    'celular1',
    'celular2',
    'direccion',
    'estado_civil',
    'lugar_trabajo',
    'cargo',
    'independiente',
    'foto', 'activo'
  ];

  public function credits()
  {
    return $this->hasMany(Credit::class);
  }
}
