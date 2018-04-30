<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'id', 'name', 'initials'
    ];

    public function documents(){
        return $this->hasMany('App\Models\Document', 'state_id');
    }

    public function mesoregion(){
        return $this->hasMany('App\Models\Mesoregion', 'state');
    }

    public function region(){
        return $this->belongsTo('App\Models\Region', 'region');
    }

}
