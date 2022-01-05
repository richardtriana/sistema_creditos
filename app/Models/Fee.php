<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
  use HasFactory;


  protected $fillable = [
    'credit_id',
    'nro_cuota',
    'valor',
    'fecha_pago',
    'dias_mora',
    'valor_interes_mora',
    'valor_pago_interes',
    'valor_pago_capital',
    'registro_pago',
    'estado'
  ];

  public function credit()
  {
    return $this->belongsTo(Credit::class, 'credit_id');
  }
}
