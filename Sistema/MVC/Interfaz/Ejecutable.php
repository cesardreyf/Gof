<?php

namespace Gof\Sistema\MVC\Interfaz;

/**
 * Interfaz para los procesos de la aplicación
 *
 * @package Gof\Sistema\MVC\Interfaz
 */
interface Ejecutable
{
    /**
     * Ejecuta la tarea que le corresponde
     */
    public function ejecutar();
}
