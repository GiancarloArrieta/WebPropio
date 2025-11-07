<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_rol',
        'departamento',
        'puesto'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasRole(int $roleId): bool
    {
        return $this->id_rol === $roleId;
    }

    // Métodos de conveniencia
    public function isAdmin(): bool
    {
        // Asumiendo que el ID 1 es el Administrador
        return $this->id_rol === 1; 
    }

    public function isDepartamento(): bool
    {
        // Asumiendo que el ID 2 es el Departamento
        return $this->id_rol === 2; 
    }
    
    public function isUsuario(): bool
    {
        // Asumiendo que el ID 3 es el Usuario Básico
        return $this->id_rol === 3; 
    }
}
