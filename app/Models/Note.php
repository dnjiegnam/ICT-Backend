<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'lecturer_id',
        'student_id',
        'semester_id',
        'note',
    ];
    use HasFactory;

    public function lecturer(){

        return $this->hasOne(User::class, 'id', 'lecturer_id');
    
    }

    public function semester(){

        return $this->hasOne(Semester::class, 'id', 'semester_id');
    
    }
}
