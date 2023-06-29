<?php

namespace Gof\Sistema\MVC\Registros;

use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\MVC\Registros\Datos\Error;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorGuardable;
use Gof\Sistema\MVC\Registros\Modulo\Operacion;

/**
 * Gestor de errores de sistema
 *
 * Clase que proporciona un conjunto de funcionalidades para almacenar y
 * registrar errores de PHP.
 *
 * Esta clase contiene un método llamado **registrar** la cual debe ser
 * empleada por la función de PHP **register_shutdown_function**.
 *
 * @package Gof\Sistema\MVC\Registros
 */
class Errores extends Operacion
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            new Propiedad(ErrorGuardable::class)
        );
    }

    /**
     * Registra los errores
     *
     * Recorre la lista de gestores de guardado y le pasa el error para que lo
     * guarden. El error se obtiene mediante la función de PHP **error_get_last**().
     *
     * Esta función debe ser registrada por **register_shutdown_function**().
     */
    public function registrar()
    {
        $ultimoError = $this->obtenerUltimoError();

        if( empty($ultimoError) ) {
            return;
        }

        $ultimoError = new Error(
            $ultimoError['type'],
            $ultimoError['message'],
            $ultimoError['file'],
            $ultimoError['line']
        );

        $gestoresDeGuardado = $this->guardado()->lista();

        array_walk($gestoresDeGuardado, function(ErrorGuardable $error) use ($ultimoError) {
            $error->guardar($ultimoError);
        });
    }

    /**
     * Obtiene el último error ocurrido
     *
     * Devuelve un array con las siguientes claves: type, message, file y line.
     * Si no hay errores se devuelve un array vacío.
     *
     * @return array
     */
    public function obtenerUltimoError(): array
    {
        return error_get_last() ?? [];
    }

}
