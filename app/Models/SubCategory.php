<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $guarded = ['id'];
    protected $fillable = [
        'category_id', // Foreign Key ke Parent
        'name',
        'slug',
    ];

    /**
     * Relasi ke Parent (Category)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Anak (Skill)
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }
}
