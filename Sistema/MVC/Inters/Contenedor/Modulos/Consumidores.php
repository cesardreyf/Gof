<?php

namespace Gof\Sistema\MVC\Inters\Contenedor\Modulos;

/**
 * Módulo encargado de gestionar la lista de un consumidor
 *
 * @package Gof\Sistema\MVC\Inters\Contenedor\Modulos
 */
class Consumidores
{
    /**
     * Almacena la lista de consumidores
     *
     * @var array
     */
    private array $lista;

    /**
     * Id del consumidor con la que trabajará
     *
     * @var int
     */
    private int $id;

    /**
     * Construtor
     *
     * @param array &$lista Referenia a la lista de consumidores.
     * @param int    $id    Id del consumidor.
     */
    public function __construct(array &$lista, int $id)
    {
        $this->id = $id;
        $this->lista =& $lista;

        if( !isset($this->lista[$id]) ) {
            $this->lista[$id] = [];
        }
    }

    /**
     * Agrega un inter a la lista al consumidor
     *
     * Si el inter ya existe en la lista no se vuelve a agregar.
     *
     * @param int $id Id del inter
     */
    public function agregar(int $id)
    {
        if( !in_array($id, $this->lista[$this->id]) ) {
            $this->lista[$this->id][] = $id;
        }
    }

    /**
     * Remueve el inter de la lista
     *
     * @param int $id Id del inter
     */
    public function remover(int $id)
    {
        $this->lista[$this->id] = array_filter($this->lista[$this->id], function($idInter) use ($id) {
            return $idInter !== $id;
        });
    }

    /**
     * Obtiene los ID de todos los inters almacenados por el consumidor
     *
     * @return int[]
     */
    public function obtenerTodos(): array
    {
        return $this->lista[$this->id];
    }

}
