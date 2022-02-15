<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
  use HasFactory;


  protected $fillable = [
    'credit_id',
    'installment_number',
    'value',
    'payment_date',
    'days_past_due',
    'late_interests_value',
    'interest_value',
    'capital_value',
    'paid_balance',
    'payment_register',
    'status',
    'capital_balance'
  ];

  public function credit()
  {
    return $this->belongsTo(Credit::class, 'credit_id');
  }
}
