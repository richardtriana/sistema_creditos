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
        'headquarter_id',
        'user_id',
        'number_installments',
        'number_paid_installments',
        'day_limit',
        'debtor',
        'status',
        'start_date',
        'interest',
        'annual_interest_percentage',
        'porcentaje_interes_mensual',
        'installment_value',
        'credit_value',
        'paid_value',
        'capital_value',
        'interest_value',
        'disbursement_date',
        'description'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function headquarter()
    {
        return $this->belongsTo(Headquarter::class, 'headquarter_id');
    }
    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
