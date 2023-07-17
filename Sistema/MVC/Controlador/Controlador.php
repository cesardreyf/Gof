<?php

namespace Gof\Sistema\MVC\Controlador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Gestor del controlador del sistema MVC
 *
 * Módulo encargado de la creación y ejecución del controlador.
 *
 * Este módulo se encarga de reservar un proceso en la pila de procesos de
 * prioridad baja de la aplicación para la creación y ejecución del
 * controlador.
 *
 * @package Gof\Sistema\MVC\Controlador
 */
class Controlador implements Ejecutable
{
    /**
     * @var Autoload Instancia del gestor de autoload
     */
    private Autoload $autoload;

    /**
     * @var Procesos Gestor de procesos de la aplicación
     */
    private Procesos $procesos;

    /**
     * Constructor
     *
     * @param Autoload $autoload Instancia del gestor de autoload
     * @param Procesos $procesos Instancia del gestor de procesos de la aplicación
     */
    public function __construct(Autoload $autoload, Procesos $procesos)
    {
        $this->autoload = $autoload;
        $this->procesos = $procesos;
    }

    /**
     * Reserva un proceso de prioridad baja para la ejecución del controlador
     *
     * Reserva el primer proceso de la pila de procesos de prioridad baja y
     * aloja en él el ejecutor del controlador.
     *
     * El ejecutor será ejecutado por la aplicación con un DAP de prioridad
     * baja, razón por la cual el nombre de la clase y los argumentos del
     * controlador son pasados en este momento de la ejecución del módulo.
     *
     * @param DAP $dapn1 Datos de acceso público de nivel 1.
     *
     * @see Ejecutor
     */
    public function ejecutar(DAP $dapn1)
    {
        $this->procesos->agregar(new Ejecutor($this->autoload, $dapn1), Prioridad::Baja);
    }

}
