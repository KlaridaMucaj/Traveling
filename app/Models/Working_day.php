<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Working_day extends Model
{
    use HasFactory;


    //protected $table = 'Working_day';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
