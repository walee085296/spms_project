<?php

namespace App\Models;

use App\Enums\GroupState;
use App\Enums\ProjectType;
use App\Enums\Specialization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Pivots\GroupUser;

class Group extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'state',
        'spec',
        'project_type'
    ];
    protected $casts = [
        'state' => GroupState::class,
        'spec' => Specialization::class,
        'project_type' => ProjectType::class
    ];
    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['search'] ?? false,
            fn ($query, $search) =>
            $query->where('id', '=', $search)
                ->orWherehas('developers', fn ($query) =>
                $query->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhereRaw("concat(first_name,' ',last_name) like '%{$search}%'"))
        );
    }

    
    public function project()
    {
        return $this->belongsTo(Project::class)->withDefault();
    }
    public function developers()
    {
        return $this->belongsToMany(User::class)->using(GroupUser::class);
    }
}
