<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'name',
        'leader_name',
        'leader_email',
        'leader_career',
        'leader_semester',
        'leader_experience',
        'max_members',
        'visibility',
        'requirements',
        'invite_code',
        'team_logo',
        'description',
        'skills_needed',
        'project_name',
        'project_file_path',
    ];

    // Relación 1: El creador del equipo (Líder principal)
    public function leader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación 2: El evento al que pertenece
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relación 3: TODOS los usuarios vinculados (Integrantes)
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot(['role', 'status'])
            ->withTimestamps();
    }

    // Relación 4: EVALUACIONES (¡Esta es la que faltaba!)
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    // --- FUNCIONES DE AYUDA ---

    // Obtener solo los miembros aceptados
    public function activeMembers()
    {
        return $this->users()->wherePivot('status', 'accepted');
    }

    // Obtener las solicitudes pendientes
    public function pendingRequests()
    {
        return $this->users()->wherePivot('status', 'pending');
    }
}
