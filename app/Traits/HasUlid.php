<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUlids;

trait HasUlid
{
    // Usamos el generador nativo de Laravel de muy alto rendimiento
    use HasUlids;

    /**
     * Le indica a Laravel en qué columnas debe inyectar un ULID automáticamente
     * antes de guardar en la base de datos.
     */
    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    /**
     * Sigue forzando a que las rutas usen el ULID (Route Model Binding)
     */
    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
