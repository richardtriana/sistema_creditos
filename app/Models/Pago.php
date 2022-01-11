<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
  use HasFactory;

  protected $table = 'pago';

  protected $fillable = [
    'tipo_deuda',
    'id_deuda',
    'valor_pago',
    'nro_cuota',
    'interest_value',
    'capital_value',
    'id_tercero',
    'payment_date'
  ];
}
