<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
  use HasFactory;
  protected $table = "users";

  protected $fillable = [
    'id', 'last_name', 'email', 'password', 'name', 'celular', 'address', 'type_document', 'documento', 'photo', 'sede_id', 'id_rol', 'status'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function sede()
  {
    return $this->belongsTo(Sede::class, 'sede_id');
  }
}
