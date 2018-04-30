<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $fillable = [
        'id', 'name', 'microregion'
    ];

        public function documents(){
        return $this->hasMany('App\Models\County', 'county_id');
    }

    public function microregion(){
        return $this->belongsTo('App\Models\Microregion', 'microregion');
    }

}
