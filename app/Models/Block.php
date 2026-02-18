<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $fillable = ['block_number', 'name', 'area_description'];

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }
}