<?php

namespace Gof\Sistema\MVC\Aplicacion\Interfaz;

/**
 * Interfaz para los criterios de la aplicación
 *
 * @package Gof\Sistema\MVC\Aplicacion\Interfaz
 */
interface Criterio
{
    public function ejecutar(Controlador $controlador);
}
