<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $guarded = ['id'];

    // Cast “attributes” ke array otomatis
    protected $casts = [
        'expertise_id' => 'array',
    ];
}
