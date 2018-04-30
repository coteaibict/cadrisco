<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'id', 'name', 'initials', 'region'
    ];

    public function state(){
        return $this->hasMany('App\Models\State', 'region');
    }

}
