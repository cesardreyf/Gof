<?php

namespace Gof\Sistema\MVC\Aplicacion\DAP;

use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;

/**
 * Gestiona los niveles del DAP
 *
 * Almacena diferentes niveles de Datos de Acceso Público utilizados por la aplicación.
 *
 * @package Gof\Sistema\MVC\Aplicacion\DAP
 */
class Niveles
{
    /**
     * DAP de nivel 1
     *
     * @var DAP
     */
    private DAP $n1;

    /**
     * DAP de nivel 2
     *
     * @var DAP
     */
    private DAP $n2;

    /**
     * DAP de nivel 3
     *
     * @var DAP
     */
    private DAP $n3;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->n1 = new N1();
        $this->n3 = new N3();
        $this->n2 = new N2($this->n1, $this->n3);
    }

    /**
     * Obtiene una instancia del D.A.P
     *
     * Obtiene un DAP según el que le corresponda a cada proceso dependiendo de
     * la prioridad que tenga
     *
     * @param Prioridad $prioridad Prioridad del proceso.
     *
     * @return DAP Obtiene el dap correspondiente.
     */
    public function& segunPrioridad(Prioridad $prioridad): DAP
    {
        switch( $prioridad ) {
            case Prioridad::Alta:
                return $this->n1;

            case Prioridad::Media:
                return $this->n2;

            case Prioridad::Baja:
                return $this->n3;
        }
    }

}
