<?php

namespace Gof\Sistema\MVC\Rutas;

use Exception;
use Gof\Interfaz\Enrutador\Enrutador;
use Gof\Sistema\MVC\Datos\Info;
use Gof\Sistema\MVC\Rutas\Simple\Gestor as GestorSimple;

/**
 * Gestor de rutas del sistema MVC
 *
 * @package Gof\Sistema\MVC\Rutas
 */
class Rutas
{
    /**
     * @var ?Enrutador Instancia del enrutador
     */
    private ?Enrutador $enrutador = null;

    /**
     * @var ?GestorSimple Gestor por defecto
     */
    private ?GestorSimple $gpd = null;

    /**
     * @var Info
     */
    private Info $info;

    /**
     * Constructor
     *
     * @param Info &$info Datos compartidos del sistema.
     */
    public function __construct(Info &$info)
    {
        $this->info =& $info;
    }

    /**
     * Obtiene o define el gestor de rutas
     *
     * @param ?Enrutador $enrutador Nuevo gestor de rutas o **null** para obtener el actual.
     *
     * @return ?Enrutador Devuelve una instancia del gestor de rutas actual.
     */
    public function gestor(?Enrutador $enrutador = null): ?Enrutador
    {
        return is_null($enrutador) ? $this->enrutador : $this->enrutador = $enrutador;
    }

    public function simple(): GestorSimple
    {
        if( is_null($this->gpd) ) {
            $this->gpd = new GestorSimple($this->enrutador);
        }

        return $this->gpd;
    }

    public function procesar()
    {
        if( is_null($this->enrutador) ) {
            throw new Exception('No existe ningún enrutador registrado');
        }

        $this->info->controlador = $this->enrutador->nombreClase();
        $this->info->parametros  = $this->enrutador->resto();
    }

}
