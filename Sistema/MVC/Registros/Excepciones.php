<?php

namespace Gof\Sistema\MVC\Registros;

use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionGuardable;
use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionImprimible;
use Gof\Sistema\MVC\Registros\Modulo\Operacion;
use Throwable;

/**
 * Gestor de excepciones
 *
 * Gestiona las excepciones sin atrapar del sistema.
 *
 * @package Gof\Sistema\MVC\Registros
 */
class Excepciones extends Operacion
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Propiedad(ExcepcionGuardable::class), 
            new Propiedad(ExcepcionImprimible::class)
        );
    }

    /**
     * Registra una excepción
     *
     * Recorre la lista de gestores de guardado y le pasa la excepción para que
     * lo guarden.
     *
     * Esta función debe ser registrada por **set_exception_handler**().
     *
     * @param Throwable $excepcion Excepción a ser guardada..
     */
    public function registrar(Throwable $excepcion)
    {
        if( $this->guardar ) {
            $gestoresDeGuardado = $this->guardado()->lista();

            array_walk($gestoresDeGuardado, function(ExcepcionGuardable $guardado) use ($excepcion) {
                $guardado->guardar($excepcion);
            });
        }

        if( $this->imprimir ) {
            $gestoresDeImpresion = $this->impresion()->lista();

            array_walk($gestoresDeImpresion, function(ExcepcionImprimible $impresion) use ($excepcion) {
                $impresion->imprimir($excepcion);
            });
        }
    }

}
