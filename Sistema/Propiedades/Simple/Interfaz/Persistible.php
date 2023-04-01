<?php

namespace Gof\Sistema\Propiedades\Simple\Interfaz;

/**
 * Interfaz para las propiedades persistibles
 *
 * @package Gof\Sistema\Propiedades\Simple\Interfaz
 */
interface Persistible
{
    /**
     * Persiste la propiedad
     *
     * @return int Devuelve **0** (cero) si no hubo errores.
     */
    public function persistir(): int;
}
