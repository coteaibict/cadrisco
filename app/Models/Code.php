<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'code';
//    use Notifiable;
    public $timestamps = false;

    public function itemcode(){
        return $this->hasMany('App\Models\ItemCode', 'code_id');
    }

}
