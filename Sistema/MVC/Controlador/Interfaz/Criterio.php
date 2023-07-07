<?php

namespace Gof\Sistema\MVC\Controlador\Interfaz;

use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Interfaz para los criterios de la aplicación
 *
 * @package Gof\Sistema\MVC\Controlador\Interfaz
 */
interface Criterio extends Ejecutable
{
    /**
     * Establece el controlador
     *
     * Define el controlador que deberá ser ejecutado según el criterio.
     *
     * @param Controlador $controlador Instancia del controlador.
     */
    public function controlador(Controlador $controlador);
}
