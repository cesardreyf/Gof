<?php

namespace Gof\Sistema\MVC\Rutas;

use Gof\Contrato\Enrutador\Enrutador;
use Gof\Datos\Lista\Texto\ListaDeTextos;
use Gof\Gestor\Url\Amigable\GestorUrl;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Aplicacion\DAP\N1;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Rutas\Excepcion\ConfiguracionInexistente;
use Gof\Sistema\MVC\Rutas\Excepcion\EnrutadorInexistente;
use Gof\Sistema\MVC\Rutas\Nodos\Gestor as GestorPorNodos;
use Gof\Sistema\MVC\Rutas\Simple\Gestor as GestorSimple;

/**
 * Gestor de rutas del sistema MVC
 *
 * Módulo encargado de obtener la solicitud y procesar los datos para obtener
 * el nombre de la clase del controlador y los parámetros que recibirá.
 *
 * El procesamiento del enrutamiento se delega a un gestor externo.
 *
 * @package Gof\Sistema\MVC\Rutas
 */
class Rutas implements Ejecutable
{
    /**
     * @var Configuracion Almacena los datos de configuración
     */
    public Configuracion $configuracion;

    /**
     * Procesa la solicitud y genera el nombre del controlador
     *
     * Obtiene la lista de recursos solicitados del DAP y se los pasa al
     * enrutador. El enrutador los procesa y genera un nombre de una clase
     * y un array con el resto de los recursos solicitados.
     *
     * Los datos de la clase del controlador como los parámetros que recibirá
     * son colocados en el DAP en los registros 'controlador' y 'parametros'
     * respectivamente.
     *
     * @param DAP $dap Datos de acceso público de nivel 1.
     *
     * @throws EnrutadorInexistente si no se definió el enrutador.
     */
    public function ejecutar(DAP $dap)
    {
        $enrutador = $this->configuracion->enrutador;
        $enrutador->procesar($this->obtenerSolicitud($dap));
        $dap->controlador = $enrutador->nombreClase();
        $dap->parametros = $enrutador->resto();
    }

    /**
     * Obtiene la lista de recursos solicitados
     *
     * @param N1 $dap Datos de acceso público de nivel 1
     *
     * @return ListaDeTextos
     */
    private function obtenerSolicitud(N1 $dap): ListaDeTextos
    {
        return new ListaDeTextos($dap->solicitud->lista);
    }

}
