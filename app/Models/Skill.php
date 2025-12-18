<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'sub_category_id',
        'name',
        'slug',
    ];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function experts(): BelongsToMany
    {
        return $this->belongsToMany(Expert::class, 'expert_skill', 'skill_id', 'expert_id');
    }

    public function clients(): BelongsToMany 
    {
        return $this->belongsToMany(Client::class, 'client_skill', 'skill_id', 'client_id')
            ->withTimestamps();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
