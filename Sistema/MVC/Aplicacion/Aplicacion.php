<?php

namespace Gof\Sistema\MVC\Aplicacion;

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
     * Constructor
     */
    public function __construct()
    {
        $this->lp = array_map(function() { return []; }, Prioridad::cases());
        $this->procesos = new Procesos($this->lp, ...Prioridad::cases());
    }

    /**
     * Ejecuta la aplicación
     *
     * Ejecuta todos los procesos de la aplicación.
     *
     * Los procesos se ejecutan por orden de prioridad: Alta, Media y Baja.
     * Primero se ejecutan todos los procesos de la más alta prioridad, una vez
     * terminado continúa con la siguiente y así hasta terminar. Cada proceso
     * se ejecuta en el órden en el que se agregaron.
     */
    public function ejecutar()
    {
        $hayProcesos = true;
        $prioridades = Prioridad::cases();
        $prioridad = current($prioridades)->value;

        while( $hayProcesos ) {
            $proceso = current($this->lp[$prioridad]);

            if( $proceso === false ) {
                $prioridadTmp = next($prioridades)->value ?? null;

                if( is_null($prioridadTmp) ) {
                    $prioridadTmp = $prioridad;
                    $hayProcesos = false;
                }

                reset($this->lp[$prioridad]);
                $prioridad = $prioridadTmp;
                continue;
            }

            $proceso->ejecutar();
            next($this->lp[$prioridad]);
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
