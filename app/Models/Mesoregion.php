<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesoregion extends Model
{
    protected $fillable = [
        'id', 'name', 'state'
    ];

    public function state(){
        return $this->belongsTo('App\Models\State', 'state');
    }

    public function county(){

        return $this->hasManyThrough(County::class,Microregion::class, 'mesoregion', 'microregion');

    }


    public function microregion(){
        return $this->hasMany('App\Models\Microregion', 'mesoregion');
    }

}
