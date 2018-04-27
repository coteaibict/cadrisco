<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id', 'ordinance', 'declaration', 'role', 'state_id', 'county_id', 'note', 'situation'
    ];

    public function user(){

        return $this->belongsTo('App\Models\Users', 'user_id');

    }

    public function state(){

        return $this->belongsTo('App\Models\State', 'state_id');

    }

    public function county(){

        return $this->belongsTo('App\Models\County', 'county_id');

    }


}
