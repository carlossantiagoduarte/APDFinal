<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'organizer',
        'location',
        'description',
        'email',
        'phone',
        'max_participants',
        'requirements',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'image_url',
        'documents_info',
        'banner_url',
        'modality',
        'registration_link',
        'main_category',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_category');
    }
}
