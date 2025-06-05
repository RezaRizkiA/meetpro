<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $table = 'expertises';
    protected $guarded = ['id'];

     public function category() {
        // Setiap expertise “belongTo / milik” satu category
        return $this->belongsTo(ExpertiseCategory::class, 'category_id');
    }
}
