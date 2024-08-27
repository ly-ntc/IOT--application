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
        'user_id',
    ];
    //mot action thuoc ve mot user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}   
