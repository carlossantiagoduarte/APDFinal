<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCriterion extends Model
{
    use HasFactory;

    protected $table = 'event_criteria'; // Apunta a la tabla que acabamos de crear

    protected $fillable = [
        'event_id',
        'name',
        'max_points'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}