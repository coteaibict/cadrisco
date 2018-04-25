<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCode extends Model
{
    protected $table = 'item_code';
//    use Notifiable;
    public $timestamps = false;

    public function codigo(){

        return $this->belongsTo('App\Models\Code', 'cod_id');

    }
    protected $fillable = [

        'id', 'name', 'description'

    ];

}
