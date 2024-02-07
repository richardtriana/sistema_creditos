<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditProvider extends Model
{
	use HasFactory;

	protected $fillable = [
		'credit_id',
		'provider_id',
		'headquarter_id',
		'last_editor',
		'credit_value',
		'paid_value',
		'pending_value',
		'last_paid',
		'status'
	];

	protected $with = [
		'provider',
		'credit'
	];

	protected $appends = [
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id', 'last_editor');
	}

	public function credit()
	{
		return $this->belongsTo(Credit::class, 'credit_id')->with('client');
	}

	public function provider()
	{
		return $this->belongsTo(Provider::class);
	}

	public function headquarter()
	{
		return $this->belongsTo(Headquarter::class, 'headquarter_id');
	}

	public function creditProviderPayments()
	{
		return $this->hasMany(CreditProviderPayment::class, 'credit_provider_id');
	}

	public function setStatusAttribute($value)
	{
		if ($this->attributes['pending_value']) {
			return  0;
		} else {
			return 1;
		}
	}
}
