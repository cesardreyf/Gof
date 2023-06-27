<?php

namespace Gof\Interfaz\Errores;

use Gof\Interfaz\Lista;

/**
 * Interfaz para clases que representen una pila de mensajes de errores
 *
 * @package Gof\Interfaz\Errores
 */
interface Errores extends Lista
{
    /**
     * Valida si existen errores o no
     *
     * @return bool Devuelve **true** si existen errores o **false** de lo contrario.
     */
    public function hay(): bool;

    /**
     * Obtiene el error
     *
     * @return int Devuelve el útlimo error
     */
    public function error(): int;

    /**
     * Limpia los errores registrados
     */
    public function limpiar();
}
