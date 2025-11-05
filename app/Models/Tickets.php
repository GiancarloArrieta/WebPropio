<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Define el nombre de la tabla de la base de datos.
     * Si tu tabla se llama 'tickets', esta línea es opcional pero recomendada.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * Define la clave primaria.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Los atributos que se pueden asignar masivamente (mass assignable).
     * Esto previene un error de seguridad al usar Ticket::create().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_usuario',
        'id_encargado',
        'título',
        'descripción',
        'id_estatus',
        'observaciones',
        'fecha_hora_reporte',
    ];

    // --- RELACIONES DE ELOQUENT ---

    /**
     * Un ticket pertenece a un usuario (el creador).
     */
    public function usuario()
    {
        // Asumiendo que tu modelo de usuario se llama 'Usuario'
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Un ticket puede ser asignado a un encargado (nullable).
     */
    public function encargado()
    {
        // Asumiendo que tu modelo de usuario se llama 'Usuario'
        return $this->belongsTo(Usuario::class, 'id_encargado');
    }

    /**
     * Un ticket tiene un estatus (Abierto, Cerrado, etc.).
     */
    public function estatus()
    {
        // Asumiendo que tu modelo de estatus se llama 'Estatus'
        return $this->belongsTo(Estatus::class, 'id_estatus');
    }
}
