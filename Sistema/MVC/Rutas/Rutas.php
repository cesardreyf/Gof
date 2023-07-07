<?php

namespace Gof\Sistema\MVC\Rutas;

use Gof\Interfaz\Enrutador\Enrutador;
use Gof\Sistema\MVC\Datos\Info;
use Gof\Sistema\MVC\Rutas\Excepcion\EnrutadorInexistente;
use Gof\Sistema\MVC\Rutas\Nodos\Gestor as GestorPorNodos;
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
     * @var mixed Simplificador de gestor por defecto
     */
    private mixed $gpd = null;

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

    /**
     * Procesa la solicitud y genera el controlador
     *
     * Obtiene el nombre del controlador y los parámetros del enrutador.
     *
     * @throws EnrutadorInexistente si no se definió el enrutador.
     */
    public function procesar()
    {
        if( is_null($this->enrutador) ) {
            throw new EnrutadorInexistente();
        }

        // NOTA
        // Debería el gestor convertirlo en minúsculas?
        // Creo que esto debería ser trabajo del enrutador no del gestor.
        $this->info->controlador = ucfirst($this->enrutador->nombreClase());
        $this->info->parametros  = $this->enrutador->resto();
    }

    /**
     * Simplificador del enrutador simple
     *
     * Simplifica la configuración y definición del enrutador simple como predeterminado.
     *
     * @return GestorSimple
     */
    public function simple(): GestorSimple
    {
        if( is_null($this->gpd) || !$this->gpd instanceof GestorSimple ) {
            $this->gpd = new GestorSimple($this->enrutador);
            $this->gpd->lanzarExcepcion = true;
        }

        return $this->gpd;
    }

    /**
     * Simplificador del enrutador por nodos
     *
     * Simplifica la configuración y definición del enrutador por nodos como predeterminado.
     *
     * @return GestorPorNodos
     */
    public function nodos(): GestorPorNodos
    {
        if( is_null($this->gpd) || !$this->gpd instanceof GestorPorNodos ) {
            $this->gpd = new GestorPorNodos($this->enrutador);
            $this->gpd->lanzarExcepcion = true;
        }

        return $this->gpd;
    }

}
