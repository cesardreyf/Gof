<?php

namespace Gof\Sistema\MVC\Rut\Inters\Modulos;

/**
 * Módulo encargado de gestionar la lista de inters
 *
 * @package Gof\Sistema\MVC\Rut\Inters\Modulos
 */
class Inters
{
    /**
     * Almacena la lista de inters
     *
     * @var array
     */
    private array $lista;

    /**
     * Puntero al id disponible para inters
     *
     * @var int
     */
    private int $ptr;

    /**
     * Construtor
     *
     * @param array &$lista Referenia a la lista de inters
     * @param int   &$ptr   Referencia al puntero del gestor de inters
     */
    public function __construct(array &$lista, int &$ptr)
    {
        $this->lista =& $lista;
        $this->ptr =& $ptr;
    }

    /**
     * Agrega un inter a la lista
     *
     * Si el inter ya existe ignora la instrucción y devuelve
     * el ID asociado al inter.
     *
     * @param string $inter Nombre del inter a ser agregado.
     *
     * @return int Devuelve el ID del inter.
     */
    public function agregar(string $inter): int
    {
        if( isset($this->lista[$inter]) ) {
            return $this->lista[$inter];
        }

        $this->lista[$inter] = $this->ptr++;
        return $this->ptr - 1;
    }

    /**
     * Obtiene el ID de un inter
     *
     * @param string $inter Nombre del inter
     *
     * @return ?int Devuelve el id o **null** si no existe.
     */
    public function obtenerId(string $inter): ?int
    {
        return $this->lista[$inter] ?? null;
    }

    /**
     * Obtiene uno o varios inters según su id
     *
     * @param int $id          Identificador del inter
     * @param int ...$otrosIds Más identificadores
     *
     * @return string[]
     */
    public function obtenerInters(int $id, int ...$otrosIds): array
    {
        $resultado = [];
        array_unshift($otrosIds, $id);
        $listaDeIntersSegunId = array_flip($this->lista);

        foreach( $otrosIds as $idDelInter ) {
            $resultado[] = $listaDeIntersSegunId[$idDelInter];
        }

        return $resultado;
    }

}
