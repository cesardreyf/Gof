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
     * Ejecuta el controlador
     *
     * Ejecuta los siguientes métodos del controlador en orden: iniciar,
     * preindice, indice, posindice, renderizar y finalizar.
     *
     * Si al ejecutarse preindice o indice el estado Error del registro se
     * activa, el siguiente método del controlador que será llamado es
     * **error**.
     *
     * Si al ocurrir un error en preindice o indice el estado Continuar del
     * registro se activa, luego de llamarse al método **error** continuará el
     * flujo en el orden esperado.
     *
     * Si el estado Renderizar del registro está activo el método
     * **renderizar** será llamado antes de finalizar, caso contrario se
     * finalizará el controlador.
     *
     * @param IControlador $controlador
     */
    public function ejecutar(IControlador $controlador)
    {
        $this->ejecutarElControlador($controlador);
    }

    /**
     * @see Ipiperf::ejecutar()
     */
    public function ejecutarElControlador(Controlador $controlador)
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

        if( !$registro->error ) {
            $controlador->indice();
        }

        if( $registro->error ) {
            $controlador->error();
            $limpiarError();
        }

        if( !$registro->error ) {
            $controlador->posindice();
        }

        if( $registro->renderizar ) {
            $controlador->renderizar();
        }

        $controlador->finalizar();
    }

}
