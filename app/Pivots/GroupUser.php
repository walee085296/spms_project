<?php
namespace App\Pivots;

use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupUser extends Pivot {
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    public function project()
    {
        return $this->hasOneThrough(Project::class, Group::class);
    }
   
}