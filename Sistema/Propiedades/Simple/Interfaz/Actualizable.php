<?php

namespace Gof\Sistema\Propiedades\Simple\Interfaz;

/**
 * Interfaz para las propiedades actualizables
 *
 * @package Gof\Sistema\Propiedades\Simple\Interfaz
 */
interface Actualizable
{
    /**
     * Actualiza la propiedad
     *
     * @return int Devuelve **0** (cero) si no hubo errores.
     */
    public function actualizar(): int;
}
