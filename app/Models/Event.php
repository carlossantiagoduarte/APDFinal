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
        'is_active', // Asegúrate de incluir este si lo usas en el controlador
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

    /**
     * ESTA ES LA FUNCIÓN QUE FALTABA
     * Relación Muchos a Muchos con Jueces (Usuarios)
     */
    public function jueces()
    {
        // Se asume que la tabla pivote se llama 'event_juez'
        // Si tu tabla se llama diferente (ej: 'event_user'), cámbialo aquí.
        return $this->belongsToMany(User::class, 'event_juez', 'event_id', 'user_id');
    }
    public function criteria()
    {
        return $this->hasMany(EventCriterion::class);
    }
}