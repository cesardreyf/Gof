<?php

namespace Gof\Sistema\MVC\Rutas;

use Gof\Contrato\Enrutador\Enrutador;
use Gof\Gestor\Url\Amigable\GestorUrl;
use Gof\Sistema\MVC\Datos\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Rutas\Excepcion\ConfiguracionInexistente;
use Gof\Sistema\MVC\Rutas\Excepcion\EnrutadorInexistente;
use Gof\Sistema\MVC\Rutas\Nodos\Gestor as GestorPorNodos;
use Gof\Sistema\MVC\Rutas\Simple\Gestor as GestorSimple;

/**
 * Gestor de rutas del sistema MVC
 *
 * @package Gof\Sistema\MVC\Rutas
 */
class Rutas implements Ejecutable
{
    /**
     * @var DAP Referencia al DAP del sistema
     */
    private DAP $dap;

    /**
     * @var Configuracion Almacena los datos de configuración
     */
    private ?Configuracion $configuracion = null;

    /**
     * Constructor
     *
     * @param DAP &$dap Datos compartidos del sistema.
     */
    public function __construct(DAP &$dap)
    {
        $this->dap =& $dap;
    }

    /**
     * Obtiene o define el gestor de rutas
     *
     * @param ?Enrutador $enrutador Nuevo gestor de rutas o **null** para obtener el actual.
     *
     * @return ?Enrutador Devuelve una instancia del gestor de rutas actual.
     */
    public function gestor(): ?Enrutador
    {
        return $this->configuracion?->enrutador;
    }

    /**
     * Procesa la solicitud y genera el nombre del controlador
     *
     * Obtiene el nombre del controlador y los parámetros del enrutador y los
     * coloca en el DAP.
     *
     * @throws EnrutadorInexistente si no se definió el enrutador.
     */
    public function ejecutar()
    {
        $enrutador = $this->obtenerEnrutador();
        $peticion  = $this->obtenerSolicitud();

        $enrutador->procesar($peticion->lista());
        $this->dap->parametros  = $enrutador->resto();
        $this->dap->controlador = $enrutador->nombreClase();
    }

    /**
     * Obtiene o define la configuración del gestor de rutas
     *
     * @return Configuracion
     */
    public function configuracion(?Configuracion $configuracion): Configuracion
    {
        return $this->configuracion = $configuracion ?? $this->configuracion;
    }

    /**
     * Obtiene el enrutador o lanza una excepción si no existe ninguna
     *
     * @return Enrutador Devuelve la instancia del enrutador.
     *
     * @throws EnrutadorInexistente si no se registró ningún enrutador.
     *
     * @access private
     */
    private function obtenerEnrutador(): Enrutador
    {
        $configuracion = $this->configuracion;
        if( is_null($configuracion) ) {
            throw new ConfiguracionInexistente();
        }

        $enrutador = $configuracion->enrutador;
        if( is_null($enrutador) ) {
            throw new EnrutadorInexistente();
        }

        return $enrutador;
    }

    /**
     * Obtiene la solicitud para ser procesada por el enrutador
     *
     * @return GestorUrl
     *
     * @access private
     */
    private function obtenerSolicitud()
    {
        return new GestorUrl(
            $_GET[$this->configuracion->urlClave] ?? '',
            $this->configuracion->separador
        );
    }

}
