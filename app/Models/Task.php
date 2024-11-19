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
        'is_deleted',
        'template_id'
    ];
    public function user(){
        return $this->belongsTo(User::class); // A task belongs to one user
    }

    public function templates(){
        return $this->belongsTo(Template::class); // A task can belongs to one template (optional)
    }
}
