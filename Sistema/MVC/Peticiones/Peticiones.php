<?php

namespace Gof\Sistema\MVC\Peticiones;

use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Aplicacion\DAP\N1;
use Gof\Sistema\MVC\Aplicacion\DAP\Solicitud\Metodo;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Módulo encargado de establecer los datos de la solicitud
 *
 * Almacena en el DAP los datos de la solicitud y delega la generación de la
 * lista de recursos solicitados en un gestor de peticiones.
 *
 * @package Gof\Sistema\MVC\Peticiones
 */
class Peticiones implements Ejecutable
{
    /**
     * Almacena los datos de configuración del módulo
     *
     * @var Configuracion
     */
    public Configuracion $configuracion;

    /**
     * Almacena el método de solicitud
     *
     * @var string
     */
    public string $metodoDeSolicitud;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metodoDeSolicitud = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Ejecuta el gestor de peticiones y genera la lista de recursos solicitados
     *
     * Establece los datos de la solicitud en el DAP de nivel 1. En el registro
     * 'solicitud' coloca los datos de la cadena y el método de la solicitud.
     *
     * Delega en un gestor de peticiones externo la creación de la lista de
     * recursos basados en la cadena de la solicitud. Otros módulos, como el de
     * rutas, hacen uso de esta lista.
     *
     * El gestor de peticiones externo se define en la configuración del módulo.
     *
     * @param N1 $dap Datos de acceso público de nivel 1
     *
     * @see Configuracion
     */
    public function ejecutar(DAP $dap)
    {
        $dap->solicitud->cadena = $_GET[$this->configuracion->url] ?? '';
        $dap->solicitud->metodo = Metodo::from($this->metodoDeSolicitud);

        if( $this->configuracion->gestor->procesar($dap->solicitud->cadena) === true ) {
            $dap->solicitud->lista = $this->configuracion->gestor->lista();
        }
    }

}
