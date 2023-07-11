<?php

namespace Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Abstraccion;

use Gof\Sistema\MVC\Controlador\Abstraccion\Controlador as ControladorAbstracto;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Datos\Registros;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Interfaz\Controlador as ControladorInterfaz;
use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter\Gestor as Inter;

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
     * @var Registros
     */
    private Registros $registros;

    /**
     * Gestor de Inters
     *
     * @var Inter
     */
    private Inter $inter;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registros = new Registros();
        $this->inter = new Inter($this->registros);
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

    /**
     * Obtiene la instancia del gestor de inters
     *
     * @return Inter
     */
    public function inter(): Inter
    {
        return $this->inter;
    }

}
