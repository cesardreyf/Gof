<?php

namespace Gof\Sistema\MVC\Aplicacion;

use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;

/**
 * Gestor de Aplicacion
 *
 * Módulo encargado de almacenar y ejecutar los procesos
 * vitales de la aplicación.
 *
 * @package Gof\Sistema\MVC\Aplicacion
 */
class Aplicacion
{
    /**
     * @var Procesos Instancia del gestor de procesos
     */
    private Procesos $procesos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->procesos = new Procesos();
    }

    /**
     * Ejecuta la aplicación
     *
     * Ejecuta todos los procesos de la aplicación.
     */
    public function ejecutar()
    {
        $this->procesos->ejecutar();
    }

    /**
     * Obtiene el gestor de procesos
     *
     * @return Procesos
     */
    public function procesos(): Procesos
    {
        return $this->procesos;
    }

}
