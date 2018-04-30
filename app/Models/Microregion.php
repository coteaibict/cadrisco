<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Microregion extends Model
{
    protected $fillable = [
        'id', 'name', 'mesoregion'
    ];

    public function mesoregion(){
        return $this->belongsTo('App\Models\Mesoregion', 'mesoregion');
    }

    public function county(){
        return $this->hasMany('App\Models\County', 'microregion');
    }

}
