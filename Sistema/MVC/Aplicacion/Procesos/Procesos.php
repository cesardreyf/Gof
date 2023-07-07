<?php

namespace Gof\Sistema\MVC\Aplicacion\Procesos;

use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Gestor de procesos
 *
 * Módulo encargado de gestionar el almacenamiento y ejecución de los procesos
 * de la aplicación del sistema MVC.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Procesos
 */
class Procesos
{
    /**
     * Lista de procesos
     *
     * Almacena todos los procesos por prioridades y en órden de llegada.
     *
     * @var array<int, Ejecutable[]>
     */
    private array $procesos;

    /**
     * Constructor
     */
    public function __construct()
    {
        foreach( Prioridad::cases() as $prioridad ) {
            $this->procesos[$prioridad->value] = [];
        }
    }

    /**
     * Agrega un proceso a la cola
     *
     * @param Ejecutable $proceso   Instancia del proceso ejecutable.
     * @param Prioridad  $prioridad Prioridad que tendrá el proceso.
     */
    public function agregar(Ejecutable $proceso, Prioridad $prioridad)
    {
        $this->procesos[$prioridad->value][] = $proceso;
    }

    /**
     * Ejecuta todos los procesos por orden de prioridad
     *
     * Los procesos se ejecutan por orden de prioridad: Alta, Media y Baja.
     * Primero se ejecutan todos los procesos de la más alta prioridad, una vez
     * terminado continúa con la siguiente y así hasta terminar. Cada proceso
     * se ejecuta en el órden en el que se agregaron.
     */
    public function ejecutar()
    {
        array_walk_recursive($this->procesos, function(Ejecutable $proceso) {
            $proceso->ejecutar();
        });
    }

}
