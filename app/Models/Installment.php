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
    'paid_capital',
    'payment_register',
    'status',
    'capital_balance'
  ];

  // protected $appends = [
  //   'headquarter'
  // ];

  public function credit()
  {
    return $this->belongsTo(Credit::class, 'credit_id');
  }

  public function getHeadquarterAttribute()
  {
    // $headquarter = Installment::where('bill_number','LIKE','%'.$this->prefix.'%')->count();
    return $this->credit->headquarter;
  }
}
