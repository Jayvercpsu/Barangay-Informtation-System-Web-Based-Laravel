<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'event_date', 'event_time', 'location', 'color'];

    protected $casts = [
        'event_date' => 'date',
    ];
}