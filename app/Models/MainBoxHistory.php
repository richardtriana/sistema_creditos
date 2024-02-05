<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainBoxHistory extends Model
{
    use HasFactory;

    public function mainBox()
	{
		return $this->belongsTo(MainBox::class, 'main_box_id');
	}
}
