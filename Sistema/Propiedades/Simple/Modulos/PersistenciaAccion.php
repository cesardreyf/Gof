<?php

namespace Gof\Sistema\Propiedades\Simple\Modulos;

use Gof\Gestor\Acciones\Interfaz\Accion;

/**
 * Reservado
 *
 * Clase empleada por el módulo de persistencia.
 *
 * @package Gof\Sistema\Propiedades\Simple\Modulos
 */
class PersistenciaAccion implements Accion
{

    public function accionar(mixed $propiedad, string $identificador)
    {
        return $propiedad->persistir();
    }

}
