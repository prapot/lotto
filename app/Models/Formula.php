<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{

    protected $fillable = [
        'user_id','title', 'value','result'
    ];
    
}
