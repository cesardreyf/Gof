<?php

namespace Gof\Sistema\MVC\Aplicacion\Criterio;

use Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Interfaz\Controlador;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Controlador as IControlador;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Criterio;

/**
 * Criterio encargado de ejecutar un conjunto de métodos en el controlador
 *
 * Ejecuta las siguientes funciones al controlador: Iniciar, Preindice, Indice,
 * Posindice, Error y Finalizar.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Criterio
 */
class Ipiperf implements Criterio
{
    /**
     * Instancia del controlador recibido por la aplicación
     *
     * @var IControlador
     */
    private IControlador $controlador;

    /**
     * Ejecuta el controlador
     *
     * Ejecuta los siguientes métodos del controlador en orden: iniciar,
     * preindice, indice, posindice, renderizar y finalizar.
     *
     * Si al ejecutarse preindice o indice el registro ERROR se activa, el
     * siguiente método del controlador que será llamado es **error**.
     *
     * Si al ocurrir un error en las funciones preindice o indice el registro
     * CONTINUAR se activa, luego de llamarse al método **error** continuará el
     * flujo en el orden esperado.
     *
     * Si el registro SALTAR se activa se saltarán la siguiente función: indice
     * y/o posindice. El registro CONTINUAR no tiene efecto si el registro
     * SALTAR se encuentra activo.
     *
     * Si el registro RENDERIZAR está activo el método **renderizar** será
     * llamado antes de finalizar, caso contrario se finalizará el controlador.
     *
     * @param IControlador $controlador
     */
    public function ejecutar()
    {
        $this->ejecutarControlador($this->controlador);
    }

    /**
     * Define el controlador a ejecutar
     *
     * @param IControlador $controlador Instancia del controlador
     */
    public function controlador(IControlador $controlador)
    {
        $this->controlador = $controlador;
    }

    /**
     * Ejecuta el controlador según un criterio
     *
     * @param Controlador $controlador Instancia del controlador que implementa la interfaz esperada
     */
    public function ejecutarControlador(Controlador $controlador)
    {
        $controlador->iniciar();
        $controlador->preindice();

        $registro = $controlador->registros();
        $limpiarError = function() use (&$registro) {
            if( $registro->error && $registro->continuar ) {
                $registro->continuar = false;
                $registro->error = false;
            }
        };

        if( $registro->error ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$registro->error && !$registro->saltar ) {
            $controlador->indice();
        }

        if( $registro->error ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$registro->error && !$registro->saltar ) {
            $controlador->posindice();
        }

        if( $registro->renderizar ) {
            $controlador->renderizar();
        }

        $controlador->finalizar();
    }

}
