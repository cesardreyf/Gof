<?php

namespace Gof\Sistema\MVC\Interfaz;

use Gof\Sistema\MVC\Aplicacion\DAP\DAP;

/**
 * Interfaz para los procesos de la aplicación
 *
 * @package Gof\Sistema\MVC\Interfaz
 */
interface Ejecutable
{
    /**
     * Ejecuta la tarea que le corresponde
     *
     * @param DAP $dap Datos de acceso público.
     */
    public function ejecutar(DAP $dap);
}
