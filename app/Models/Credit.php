<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $table = 'credits';

    protected $fillable = [
        'client_id',
        'deudor_id',
        'sede_id',
        'cant_cuotas',
        'cant_cuotas_pagadas',
        'dia_limite',
        'deudor',
        'status',
        'fecha_inicio',
        'interes',
        'porcentaje_interes_anual',
        'porcentaje_interes_mensual',
        'usu_crea',
        'valor_cuota',
        'valor_credit',
        'valor_abonado',
        'capital_value',
        'interest_value',
    ];
 
    protected $with = [
        'client'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function deudor()
    {
        return $this->belongsTo(Client::class, 'deudor_id');
    }

    public function asesor()
    {
        return $this->belongsTo(User::class, 'usu_crea');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }
    public function installments()
    {
        return $this->hasMany(Installment::class, 'credit_id');
    }
}
