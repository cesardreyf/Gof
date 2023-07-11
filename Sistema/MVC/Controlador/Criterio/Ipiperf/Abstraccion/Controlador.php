<?php

namespace Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Abstraccion;

use Gof\Sistema\MVC\Controlador\Abstraccion\Controlador as ControladorAbstracto;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Datos\Registros;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Interfaz\Controlador as ControladorInterfaz;

/**
 * Clase abstracta para un controlador según el criterio Ipiperf
 *
 * @package Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Abstraccion
 */
abstract class Controlador extends ControladorAbstracto implements ControladorInterfaz
{
    /**
     * Almacena los registros que alteran la ejecución del controlador
     *
     * @return Registros
     */
    public Registros $registros;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registros = new Registros();
    }

    /**
     * Obtiene los registros del controlador
     *
     * @return Registros
     */
    public function registros(): Registros
    {
        return $this->registros;
    }

}
