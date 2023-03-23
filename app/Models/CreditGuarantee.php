<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditGuarantee extends Model
{
    use HasFactory;
    
    protected $table = 'credit_guarantee';
    
    protected $fillable = [
        'credit_id',
        'guarantee_id',
        'date_add'
    ];
}
