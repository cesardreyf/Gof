<?php

namespace Gof\Sistema\MVC\Controlador\Criterio;

use Gof\Sistema\MVC\Controlador\Criterio\Ipiperf\Interfaz\Controlador;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorIndefinido;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Controlador\Interfaz\Controlador as IControlador;
use Gof\Sistema\MVC\Controlador\Interfaz\Criterio;

/**
 * Criterio encargado de ejecutar un conjunto de métodos en el controlador
 *
 * Ejecuta las siguientes funciones al controlador: Iniciar, Preindice, Indice,
 * Posindice, Error y Finalizar.
 *
 * @package Gof\Sistema\MVC\Controlador\Criterio
 */
class Ipiperf implements Criterio
{
    /**
     * Instancia del controlador recibido por la aplicación
     *
     * @var IControlador
     */
    private ?IControlador $controlador = null;

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
     * @throws ControladorIndefinido si el controlador no se definió.
     * @throws ControladorInvalido si el controlador no es válido.
     */
    public function ejecutar()
    {
        $controlador = $this->obtenerControlador();
        $registros = $controlador->registros();
        $vale = false; //< Volver A LLamar Error
        $limpiarError = function() use (&$controlador, &$registros) {
            if( $registros->error && $registros->continuar ) {
                $registros->continuar = false;
                $registros->error = false;
            }
        };

        $controlador->iniciar();

        if( !$registros->saltar ) {
            $controlador->preindice();
        }

        if( $registros->error ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$registros->error && !$registros->saltar ) {
            $controlador->indice();

            if( $registros->error ) {
                $vale = true;
            }
        }

        if( $registros->error && $vale ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$registros->error && !$registros->saltar ) {
            $controlador->posindice();
        }

        if( $registros->renderizar ) {
            $controlador->renderizar();
        }

        $controlador->finalizar();
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
        $this->controlador = $controlador;
    }

    /**
     * Valida la instancia del controlador y devuelve la instancia
     *
     * @return Controlador
     *
     * @throws ControladorIndefinido si el controlador no se definió.
     * @throws ControladorInvalido si el controlador no es válido.
     *
     * @access private
     */
    private function obtenerControlador(): Controlador
    {
        if( is_null($this->controlador) ) {
            throw new ControladorIndefinido();
        }

        if( !$this->controlador instanceof Controlador) {
            throw new ControladorInvalido(get_class($this->controlador), Controlador::class);
        }

        return $this->controlador;
    }

}
