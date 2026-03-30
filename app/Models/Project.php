<?php

namespace App\Models;

use App\Models\User;
use App\Models\Group;
use App\Enums\ProjectType;
use App\Enums\ProjectState;
use App\Enums\projectSup;
use App\Enums\Specialization;
use App\Pivots\GroupUser;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'url',
        'sup',
        'spec',
        'type',
        'state',
        'aims',
        'objectives',
        'tasks',
        'supervisor_id',
    ];

    protected $casts = [
        'spec' => Specialization::class,
        'type' => ProjectType::class,
        'state' => ProjectState::class,
        'sup'=> projectSup::class,
         
    ];
  public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn ($query, $search) =>
        $query->where(
            fn ($query) => $query->where('title', 'LIKE', '%' . $search . '%')
                ->orWhereHas('supervisor', fn ($query) => $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhereRaw("concat(first_name,' ',last_name) like '%{$search}%'"))
                ->orWhereHas('group', fn ($query) => $query->whereHas('developers', fn ($query) => $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhereRaw("concat(first_name,' ',last_name) like '%{$search}%'")))
        ))
            ->when(
                $filters['type'] ?? false,
                fn ($query, $type) => $query->where('type', '=', $type)
            )
            ->when(
                $filters['spec'] ?? false,
                fn ($query, $spec) => $query->where('spec', '=', $spec)
            )
            ->when(
                $filters['state'] ?? false,
                fn ($query, $state) => $query->where('state', '=', $state)
            )
            ->when(
                $filters['created_from'] ?? false,
                fn ($query, $created_from) => $query->whereDate('created_at', '>=', Carbon::parse($created_from)->format('Y-m-d'))
            )
            ->when(
                $filters['created_to'] ?? false,
                fn ($query, $created_to) => $query->whereDate('created_at', '<=',  Carbon::parse($created_to)->format('Y-m-d'))
            )
            ->when(
                $filters['updated_from'] ?? false,
                fn ($query, $updated_from) => $query->whereDate('updated_at', '>=',  Carbon::parse($updated_from)->format('Y-m-d'))
            )
            ->when(
                $filters['updated_to'] ?? false,
                fn ($query, $updated_to) => $query->whereDate('updated_at', '<=',  Carbon::parse($updated_to)->format('Y-m-d'))
            );
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'id')->withDefault();
    }
    public function group()
    {
        return $this->hasOne(Group::class)->withDefault();
    }
    public function getDevelopersAttribute()
    {
        return $this->group->load('developers')->developers;
    }
}
