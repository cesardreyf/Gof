<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Acciones\Interfaz\Accion;

/**
 * Reservado
 *
 * Clase empleada por el módulo de borrado.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class BorradoAccion implements Accion
{

    public function accionar(mixed $propiedad, string $identificador)
    {
        return $propiedad->borrar();
    }

}
