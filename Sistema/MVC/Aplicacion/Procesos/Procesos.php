<?php

namespace Gof\Sistema\MVC\Aplicacion\Procesos;

use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Gestor de procesos
 *
 * Módulo encargado de gestionar el almacenamiento y ejecución de los procesos
 * de la aplicación del sistema MVC.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Procesos
 */
class Procesos implements Lista
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
     *
     * @param array &$lp Referencia a la lista de procesos
     */
    public function __construct(array &$lp)
    {
        $this->procesos =& $lp;

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
     * Obtiene un módulo que solo puede agregar procesos a una única prioridad
     *
     * @return Agregable
     */
    public function agregable(Prioridad $prioridad): Agregable
    {
        return new Agregable($this->lp[$prioridad->value]);
    }

    /**
     * Obtiene la lista de procesos almacenados
     *
     * @return array
     */
    public function lista(): array
    {
        return $this->procesos;
    }

}
