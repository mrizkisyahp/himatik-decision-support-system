<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkGapWeight extends Model
{
    protected $fillable = [
        'gap',
        'weight',
        'description',
    ];
}
