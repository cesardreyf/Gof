<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Acciones\Interfaz\Accion;

/**
 * Reservado
 *
 * Clase empleada por el módulo de actualización.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class ActualizacionAccion implements Accion
{

    public function accionar(mixed $propiedad, string $identificador)
    {
        return $propiedad->actualizar();
    }

}
