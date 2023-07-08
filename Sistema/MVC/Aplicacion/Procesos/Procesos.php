<?php

namespace Gof\Sistema\MVC\Aplicacion\Procesos;

use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Aplicacion\Excepcion\PermisosInsuficientes;
use Gof\Sistema\MVC\Aplicacion\Excepcion\PrioridadIlegal;
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
     * Lista de prioridades habilitadas
     *
     * Almacena las prioridades habilitadas como claves.
     *
     * @var array
     */
    private array $prioridades;

    /**
     * Constructor
     *
     * @param array       &$lp          Referencia a la lista de procesos
     * @param Prioridad    $prioridad   Prioridad habilitada
     * @param Prioridad ...$prioridades Lista de prioridades habilitadas (Opcional)
     */
    public function __construct(array &$lp, Prioridad $prioridad, Prioridad ...$prioridades)
    {
        $this->procesos =& $lp;
        array_unshift($prioridades, $prioridad);
        $this->prioridades = $prioridades;
    }

    /**
     * Agrega un proceso a la cola
     *
     * @param Ejecutable $proceso   Instancia del proceso ejecutable.
     * @param Prioridad  $prioridad Prioridad que tendrá el proceso.
     */
    public function agregar(Ejecutable $proceso, Prioridad $prioridad)
    {
        if( !in_array($prioridad, $this->prioridades) ) {
            throw new PrioridadIlegal($prioridad);
        }

        $this->procesos[$prioridad->value][] = $proceso;
    }

    /**
     * Genera una instancia de la misma clase con nuevas prioridades
     *
     * @return Procesos
     */
    public function agregable(Prioridad $prioridad, Prioridad ...$prioridades): self
    {
        array_unshift($prioridades, $prioridad);

        if( !$this->tienePermisos(...$prioridades) ) {
            throw new PermisosInsuficientes($this->prioridades);
        }

        return new self($this->procesos, ...$prioridades);
    }

    /**
     * Verifica si esta instancia tiene permisos para dar prioridades
     *
     * Valida que la instancia de esta clase tenga la misma prioridad que
     * quiere otorgar. Si es válido la función devuelve **true**.
     *
     * @return bool Devuelve **true** si tiene permisos.
     */
    public function tienePermisos(Prioridad ...$prioridades): bool
    {
        foreach( $prioridades as $prioridadSolicitada ) {
            if( !in_array($prioridadSolicitada, $this->prioridades) ) {
                return false;
            }
        }
        return true;
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
