<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeHomeExam extends Model
{
    /** @use HasFactory<\Database\Factories\TakeHomeExamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
    ];
}
