<?php

namespace Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter;

use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Datos\Registros;

/**
 * Datos de Acceso Públic para los inters
 *
 * @package Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Inter
 */
class DAP
{
    /**
     * Instancia de los registros del controlador
     *
     * @var Registros
     */
    public Registros $registros;

    /**
     * Estado que indica saltar el/las siguiente/s inter's
     *
     * @var int Cantidad de Inter a saltarse.
     */
    public int $saltar = 0;

    /**
     * Estado que al establecerse a true rompe la ejecución de inter
     *
     * @var bool
     */
    public bool $romper = false;

    /**
     * Estado que indica ejecutar el controlador hasta que este valor sea false
     *
     * Al activar este registro el inter se ejecutará "infinitamente" hasta que
     * el valor del registro sea nuevamente false.
     *
     * @var bool
     */
    public bool $mientras = false;

    /**
     * Array público
     *
     * Array donde todos los inter podrán escribir y leer para
     * intercomunicarse entre ellos.
     *
     * @var array
     */
    public array $memoria = [];

    /**
     * Constructor
     *
     * @param Registros $registros Registros del controlador.
     */
    public function __construct(Registros $registros)
    {
        $this->registros = $registros;
    }

}
