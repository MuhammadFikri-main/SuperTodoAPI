<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\UserTemplateFactory> */
    use HasFactory;

    protected $fillable = [
        'userID',
        'templateID'
    ];
}
