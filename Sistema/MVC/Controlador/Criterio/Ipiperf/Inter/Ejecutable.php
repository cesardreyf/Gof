<?php

namespace Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter;

/**
 * Interfaz que deben implementar los inters
 *
 * @package Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter
 */
interface Ejecutable
{
    /**
     * Ejecuta el Inter
     *
     * Recibe como argumento la instancia del DAP del gestor de inters. El
     * mismo es capaz de alterar el comportamiento de la ejecución de los
     * inters.
     *
     * @param DAP $dap Datos de acceso público.
     */
    public function ejecutar(DAP $dap);
}
