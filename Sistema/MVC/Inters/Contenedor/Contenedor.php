<?php

namespace Gof\Sistema\MVC\Inters\Contenedor;

use Gof\Sistema\MVC\Inters\Contenedor\Modulos\Consumidores;
use Gof\Sistema\MVC\Inters\Contenedor\Modulos\Grupos;
use Gof\Sistema\MVC\Inters\Contenedor\Modulos\Inters;

/**
 * Contenedor de inters para los consumidores
 *
 * Gestiona el almacenamiento de nombres de inters para un consumidor.
 *
 * @package Gof\Sistma\MVC\Inters\Contenedor
 */
class Contenedor
{

    /**
     * Módulo de inters
     *
     * @var Inters
     */
    private Inters $inters;

    /**
     * Módulo de consumidores
     *
     * @var Consumidores
     */
    private Consumidores $consumidores;

    /**
     * Módulo de grupos
     *
     * @var Grupos
     */
    private Grupos $grupos;

    /**
     * Constructor
     *
     * @param int    $cid          Id del consumidor.
     * @param array &$inters       Referencia a la lista de inters.
     * @param array &$consumidores Referencia a la lista de consumidores.
     * @param int   &$ptr          Referencia al puntero interno del gestor.
     * @param array &grupos        Referencia a la lista de grupos.
     */
    public function __construct(int $cid, array &$inters, array &$consumidores, int &$ptr, array &$grupos)
    {
        $this->inters       = new Inters($inters, $ptr);
        $this->consumidores = new Consumidores($consumidores, $cid);
        $this->grupos       = new Grupos($grupos, $this->inters, $this->consumidores);
    }

    /**
     * Agrega un inter al contenedor
     *
     * @param string $inter     Nombre del inter.
     * @param stirng ...$inters Más nombres de inters
     */
    public function agregar(string $inter, string ...$inters)
    {
        array_unshift($inters, $inter);
        foreach( $inters as $inter ) {
            $idDelInter = $this->inters->agregar($inter);
            $this->consumidores->agregar($idDelInter);
        }
    }

    /**
     * Remueve un inter del contenedor
     *
     * @param string $inter     Nombre del inter a remover
     * @param stirng ...$inters Más nombres de inters
     */
    public function remover(string $inter, string ...$inters)
    {
        array_unshift($inters, $inter);
        foreach( $inters as $inter ) {
            if( ($idDelInter = $this->inters->obtenerId($inter)) !== null ) {
                $this->consumidores->remover($idDelInter);
            }
        }
    }

    /**
     * Obtiene un array con todos los inters contenido
     *
     * @return string[]
     */
    public function obtener(): array
    {
        $listaConLosIdsDeLosInters = $this->consumidores->obtenerTodos();
        return empty($listaConLosIdsDeLosInters) ? [] : $this->inters->obtenerInters(...$listaConLosIdsDeLosInters);
    }

    /**
     * Obtiene el submódulo encargado de gestionar los grupos de inters
     *
     * @return Grupos
     */
    public function grupo(): Grupos
    {
        return $this->grupos;
    }

}
