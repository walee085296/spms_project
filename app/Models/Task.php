<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'url',
        'comment',
        'desc'
    ];

     protected $casts = [
    'desc' => 'array',
    ];

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }


       public function supervisedProjects()
    {
    return $this->hasMany(Project::class, 'supervisor_id');
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }

   
 public function project()
    {
        return $this->belongsTo(Project::class);
    }
  
}
