<?php

namespace Gof\Sistema\MVC\Inters\Contenedor\Modulos;

use Gof\Sistema\MVC\Inters\Contenedor\Excepcion\ExcepcionGrupoExistente;
use Gof\Sistema\MVC\Inters\Contenedor\Excepcion\ExcepcionGrupoInexistente;

/**
 * Módulo encargado de gestionar los grupos de inters
 *
 * Este móduo está a cargo de la gestión de los grupos de inters y de su
 * asociación con las rutas.
 *
 * El módulo permite la creación de grupos donde se pueden almacenar múltiples
 * inters. Estos grupos pueden ser asociados a los consumidores (rutas).
 *
 * @package Gof\Sistema\MVC\Inters\Contenedor\Modulos
 */
class Grupos
{

    /**
     * Constructor
     *
     * @param array       &$grupos       Referencia a la lista de grupos
     * @param Inters       $inters       Módulo de inters
     * @param Consumidores $consumidores Módulo de consumidores
     */
    public function __construct(private array &$grupos, private Inters $inters, private Consumidores $consumidores)
    {
    }

    /**
     * Crea un grupo nuevo y los asocia a los inters especificados
     *
     * @param string $grupo Nombre del grupo
     * @param string $inter Nombre de la clase del inter (obligatorio)
     * @param string ...$inters Nombres de inters (opcional)
     */
    public function crear(string $grupo, string $inter, string ...$inters)
    {
        if( isset($this->grupos[$grupo]) ) {
            throw new ExcepcionGrupoExistente($grupo);
        }

        $grupoTemporal = [];
        array_unshift($inters, $inter);

        foreach( $inters as $inter ) {
            $grupoTemporal[] = $this->inters->agregar($inter);
        }

        $this->grupos[$grupo] = array_unique($grupoTemporal);
    }

    /**
     * Asigna a los grupos especificados
     *
     * Asigna los inters asociados a los grupos al consumidor.
     *
     * @param string $grupo Nombre del grupo (obligatorio)
     * @param string ...$grupos Nombre de los grupos (opcional)
     */
    public function asignar(string $grupo, string ...$grupos)
    {
        if( !isset($this->grupos[$grupo]) ) {
            throw new ExcepcionGrupoInexistente($grupo);
        }

        foreach( $this->grupos[$grupo] as $inter ) {
            $this->consumidores->agregar($inter);
        }
    }

    // public function quitar(string $grupo, string ...$grupos)
    // {
    // }

}
