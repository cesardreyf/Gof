<?php

namespace Gof\Sistema\MVC\Aplicacion;

use Gof\Sistema\MVC\Aplicacion\DAP\Niveles;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

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
     * Lista de procesos
     *
     * @var array
     */
    private array $lp = [];

    /**
     * @var Procesos Instancia del gestor de procesos
     */
    private Procesos $procesos;

    /**
     * @var Niveles Gestor de obtención de los diferentes DAP's
     */
    private Niveles $dap;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dap = new Niveles();
        $this->lp = array_fill(0, count(Prioridad::cases()), []);
        $this->procesos = new Procesos($this->lp, ...Prioridad::cases());
    }

    /**
     * Ejecuta la aplicación
     *
     * Ejecuta todos los procesos de la aplicación.
     *
     * Los procesos se ejecutan por orden de prioridad.
     * Primero se ejecutan todos los procesos de la más alta prioridad. Una vez
     * terminado continúa con la siguiente, y así hasta terminar. Cada proceso
     * se ejecuta en el órden en el que se agregaron.
     *
     * Por cada proceso se ejecuta el método 'ejecutar' y se le pasa como
     * argumento un D.A.P (Datos de Acceso Público) según la prioridad del
     * proceso.
     */
    public function ejecutar()
    {
        $hayProcesos = true;
        $prioridades = Prioridad::cases();
        $prioridad = current($prioridades);
        $datos = $this->dap->segunPrioridad($prioridad);

        while( $hayProcesos ) {
            $proceso = current($this->lp[$prioridad->value]);

            if( $proceso === false ) {
                $prioridadTmp = next($prioridades);

                if( $prioridadTmp === false ) {
                    $prioridadTmp = $prioridad;
                    $hayProcesos = false;
                }

                reset($this->lp[$prioridad->value]);
                $prioridad = $prioridadTmp;
                $datos = $this->dap->segunPrioridad($prioridad);
                continue;
            }

            $proceso->ejecutar($datos);
            next($this->lp[$prioridad->value]);
        }
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
