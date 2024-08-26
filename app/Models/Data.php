<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    //dung bang data
    protected $table = 'datas';
    //cac cot trong bang
    protected $fillable = [
        'id',
        'temperature',
        'humidity',
        'time',
        'light',
    ];
}
