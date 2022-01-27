<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
	use HasFactory;

	protected $fillable = [
		'headquarter_id',
		'initial_balance',
		'current_balance',
		'input',
		'output',
		'history',
		'last_update',
		'last_editor'
	];

	protected $with = [
		'headquarter',
		'last_editor'
	];

	public function headquarter()
	{
		return $this->belongsTo(Headquarter::class, 'headquarter_id');
	}

	public function last_editor()
	{
		return $this->belongsTo(User::class, 'last_editor');
	}
}
