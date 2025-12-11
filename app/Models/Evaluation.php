<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    // ESTO ES LO QUE TE FALTA:
    protected $fillable = [
        'team_id',
        'user_id',
        'score',
        'feedback',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}