<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{

    protected $fillable = [
        'user_id','title', 'type','condition','round','last_round','result'
    ];


    public function values(){
        return $this->hasMany('App\Models\FormulaValue', 'formula_id', 'id');
    }
    
}