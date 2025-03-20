<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codes',
        'created_by',
        'project_id',
        'responsible_by',
        'description',
        'resolution_time',
        'response_time',
        'status',
        'uuid', // Add UUID to fillable
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'uuid' => 'string',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        // Use UUID for route model binding
        return 'uuid';
    }

    // Rest of your existing relationships and methods...
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responsibleBy()
    {
        return $this->belongsTo(User::class, 'responsible_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function sla()
    {
        return $this->belongsTo(User::class, 'sla_id');
    }

    public function scopeSearch($query, $search = null)
    {
        return $query->when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                // Search in ticket attributes
                $query->where('codes', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('uuid', 'like', '%' . $search . '%');

                // Search by status (if the search term matches status values)
                $statusMap = [
                    'open' => 0,
                    'closed' => 1,
                    'escalated' => 2,
                ];

                $normalizedSearch = strtolower(trim($search));
                if (array_key_exists($normalizedSearch, $statusMap)) {
                    $query->orWhere('status', $statusMap[$normalizedSearch]);
                }

                // Search in related models
                $query->orWhereHas('project', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%');
                });

                $query->orWhereHas('createdBy', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%');
                });

                $query->orWhereHas('responsibleBy', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        });
    }
}
