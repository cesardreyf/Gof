<?php

namespace Gof\Sistema\MVC\Rut\Inters\Modulos;

/**
 * Módulo encargado de gestionar la lista de rutas
 *
 * @package Gof\Sistema\MVC\Rut\Inters\Modulos
 */
class Rutas
{
    /**
     * Almacena la lista de rutas
     *
     * @var array
     */
    private array $lista;

    /**
     * Id de la ruta con la que trabajará
     *
     * @var int
     */
    private int $id;

    /**
     * Construtor
     *
     * @param array &$lista Referenia a la lista de rutas
     * @param int    $id    Id de la ruta
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
     * Agrega un inter a la lista interna de la ruta
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
     * Elimina un inter de la ruta
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
     * Obtiene los ID de todos los inters de la ruta
     *
     * @return int[]
     */
    public function obtenerTodos(): array
    {
        return $this->lista[$this->id];
    }

}
