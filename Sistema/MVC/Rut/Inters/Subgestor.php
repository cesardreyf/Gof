<?php

namespace Gof\Sistema\MVC\Rut\Inters;

use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Sistema\MVC\Rut\Inters\Modulos\Inters;
use Gof\Sistema\MVC\Rut\Inters\Modulos\Rutas;

/**
 * Subgestor de inters para rutas del enrutador Rut
 *
 * @package Gof\Sistma\MVC\Rut\Inters
 */
class Subgestor
{
    /**
     * Módulo de inters
     *
     * @var Inters
     */
    private Inters $moduloInters;

    /**
     * Módulo de rutas
     *
     * @var Rutas
     */
    private Rutas $moduloRutas;

    /**
     * Constructor
     *
     * @param int    $rutaId Id de la ruta.
     * @param array &$inters Referencia a la lista de inters.
     * @param array &$rutas  Referencia a la lsita de rutas.
     * @param int   &$ptr    Referencia al puntero interno del gestor.
     */
    public function __construct(int $rutaId, array &$inters, array &$rutas, int &$ptr)
    {
        $this->moduloInters = new Inters($inters, $ptr);
        $this->moduloRutas  = new Rutas($rutas, $rutaId);
    }

    /**
     * Agrega un inter a la ruta
     *
     * @param string $inter Nombre del inter.
     */
    public function agregar(string $inter)
    {
        $idDelInter = $this->moduloInters->agregar($inter);
        $this->moduloRutas->agregar($idDelInter);
    }

    /**
     * Remueve un inter de la ruta
     *
     * @param string $inter Nombre del inter a remover
     */
    public function remover(string $inter)
    {
        if( ($idDelInter = $this->moduloInters->obtenerId($inter)) !== null ) {
            $this->moduloRutas->remover($idDelInter);
        }
    }

    /**
     * Obtiene un array con todos los inters de la ruta
     *
     * @return string[]
     */
    public function obtener(): array
    {
        $listaConLosIdsDeLosInters = $this->moduloRutas->obtenerTodos();
        if( !empty($listaConLosIdsDeLosInters) ) {
            return $this->moduloInters->obtenerInters(...$listaConLosIdsDeLosInters);
        }
        return [];
    }

}
