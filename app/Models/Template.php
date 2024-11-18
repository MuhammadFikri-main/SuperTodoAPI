<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    /** @use HasFactory<\Database\Factories\TemplateFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_at',
        'is_deleted'
    ];

    public function user(){
        return $this->belongsTo(User::class); // A template belong to one user (creator)
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_templates'); // Many user can use many templates (Via the pivot table)
    }

    public function tasks(){
        return $this->hasMany(Task::class); // A template can have many tasks associated with it
    }
}
