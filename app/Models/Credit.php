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
        'debtor_id',
        'sede_id',
        'number_installments',
        'number_paid_installments',
        'day_limit',
        'debtor',
        'status',
        'fecha_inicio',
        'interest',
        'annual_interest_percentage',
        'porcentaje_interes_mensual',
        'usu_crea',
        'valor_cuota',
        'credit_value',
        'paid_value',
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

    public function debtor()
    {
        return $this->belongsTo(Client::class, 'debtor_id');
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
