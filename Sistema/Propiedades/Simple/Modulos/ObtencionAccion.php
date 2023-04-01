<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Acciones\Interfaz\Accion;

/**
 * Reservado
 *
 * Clase empleada por el módulo de obtención.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class ObtencionAccion implements Accion
{

    public function accionar(mixed $propiedad, string $identificador)
    {
        return $propiedad->obtener();
    }

}
