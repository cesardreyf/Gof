<?php

namespace Gof\Sistema\Propiedades\Simple\Interfaz;

/**
 * Interfaz para las propiedades obtenibles
 *
 * @package Gof\Sistema\Propiedades\Simple\Interfaz
 */
interface Obtenible
{
    /**
     * Obtiene la propiedad
     *
     * @return int Devuelve **0** (cero) si no hubo errores.
     */
    public function obtener(): int;
}
