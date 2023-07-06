<?php

namespace Gof\Sistema\MVC\Aplicacion\Criterio;

use Gof\Sistema\MVC\Aplicacion\Criterio\Ipiperf\Interfaz\Controlador;
use Gof\Sistema\MVC\Aplicacion\Excepcion\ControladorInvalido;
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
     *
     * @throws ControladorInvalido si el controlador no implementa la interfaz Controlador
     *
     * @see Controlador
     */
    public function controlador(IControlador $controlador)
    {
        if( !$controlador instanceof Controlador) {
            throw new ControladorInvalido(get_class($controlador), Controlador::class);
        }

        $this->controlador = $controlador;
    }

    /**
     * Ejecuta el controlador según un criterio
     *
     * @param Controlador $controlador Instancia del controlador que implementa la interfaz esperada
     */
    public function ejecutarControlador(Controlador $controlador)
    {
        // Volver A LLamar Error
        $vale = false;

        $limpiarError = function() use (&$controlador) {
            if( $controlador->registros()->error && $controlador->registros()->continuar ) {
                $controlador->registros()->continuar = false;
                $controlador->registros()->error = false;
            }
        };

        $controlador->iniciar();
        $controlador->preindice();

        if( $controlador->registros()->error ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$controlador->registros()->error && !$controlador->registros()->saltar ) {
            $controlador->indice();

            if( $controlador->registros()->error ) {
                $this->vale = true;
            }
        }

        if( $controlador->registros()->error && $vale ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$controlador->registros()->error && !$controlador->registros()->saltar ) {
            $controlador->posindice();
        }

        if( $controlador->registros()->renderizar ) {
            $controlador->renderizar();
        }

        $controlador->finalizar();
    }

}
