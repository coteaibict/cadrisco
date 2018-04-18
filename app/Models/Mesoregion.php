<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesoregion extends Model
{
    protected $fillable = [
        'id', 'name', 'state'
    ];
}
