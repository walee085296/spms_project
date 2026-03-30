<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'url',
        'desc'
    ];

     protected $casts = [
    'desc' => 'array',
    ];

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

   
 public function project()
    {
        return $this->belongsTo(Project::class);
    }
  
}
