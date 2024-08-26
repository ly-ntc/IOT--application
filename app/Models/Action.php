<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;
    //dung bang action
    protected $table = 'actions';
    //cac cot trong bang    
    protected $fillable = [
        'id',
        'action',
        'time',
    ];

}
