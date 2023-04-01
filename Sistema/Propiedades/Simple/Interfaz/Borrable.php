<?php

namespace Gof\Sistema\Propiedades\Simple\Interfaz;

/**
 * Interfaz para las propiedades borrables
 *
 * @package Gof\Sistema\Propiedades\Simple\Interfaz
 */
interface Borrable
{
    /**
     * Borra la propiedad
     *
     * @return int Devuelve **0** (cero) si no hubo errores.
     */
    public function borrar(): int;
}
