<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;

    /**
     * Define el nombre de la tabla de la base de datos.
     * Asumimos que tu tabla se llama 'estatus'.
     *
     * @var string
     */
    protected $table = 'estatus';

    /**
     * Define la clave primaria.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indica si el modelo debe usar timestamps (created_at y updated_at).
     * Si no tienes estas columnas en tu tabla 'estatus', debes establecerlo en false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Asumimos que la tabla solo tiene un campo 'nombre' además del ID.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    // --- RELACIONES DE ELOQUENT ---

    /**
     * Un estatus puede tener muchos tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_estatus');
    }
}
