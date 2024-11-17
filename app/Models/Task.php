<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'dateline',
        'created_at',
        'is_deleted'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
