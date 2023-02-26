<?php

namespace Gof\Interfaz\Errores;

/**
 * Interfaz para clases que representen una pila de mensajes de errores
 *
 * @package Gof\Interfaz\Errores
 */
interface Errores
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
     * @return int Devuelve el error
     */
    public function error(): int;

    /**
     * Obtiene el array con todos los errores
     *
     * @return array Devuelve una lista de todos los errores registrados
     */
    public function errores(): array;

    /**
     * Limpia los errores registrados
     */
    public function limpiar();
}
