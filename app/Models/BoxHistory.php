<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxHistory extends Model
{
    use HasFactory;

    public function box()
	{
		return $this->belongsTo(Box::class, 'box_id');
	}
}
