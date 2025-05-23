<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertiseCategory extends Model
{
    protected $table = 'expertise_categories';
    protected $guarded = ['id'];

    public function expertises(){
        return $this->hasMany(Expertise::class, 'category_id');
    }
}
