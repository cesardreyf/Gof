<?php

namespace Gof\Sistema\Formulario\Contratos;

use Gof\Interfaz\Lista;

/**
 * Interfaz para el gestor de errores del sistema de formulario
 *
 * Interfaz que debe implementar el gestor de errores del sistema de formulario.
 *
 * @package Gof\Sistema\Formulario\Contratos
 */
interface Errores extends Lista
{
    /**
     * Indica si hay errores
     *
     * @return bool Devuelve **true** si existen errores o **false** de lo contrario.
     */
    public function hay(): bool;
}
