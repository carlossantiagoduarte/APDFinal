<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_category');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_interests');
    }
}
