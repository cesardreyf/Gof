<?php

namespace Gof\Sistema\MVC\Registros\Datos;

use Gof\Sistema\MVC\Registros\Interfaz\Error as IError;

/**
 * Dato que almacena un error provocado por la función error_get_last().
 *
 * @package Gof\Sistema\MVC\Registros\Datos
 */
class Error implements IError
{

    /**
     * Constructor
     *
     * @param int    $tipo    Tipo de error
     * @param string $mensaje Mensaje de error
     * @param string $archivo Ruta del archivo donde ocurrió el error
     * @param int    $linea   Línea donde se produjo el error
     */
    public function __construct(
        public int    $tipo,
        public string $mensaje,
        public string $archivo,
        public int    $linea
    )
    {
    }

    /**
     * Tipo de error
     *
     * @return int
     */
    public function tipo(): int
    {
        return $this->tipo;
    }

    /**
     * Línea del archivo donde se produjo el error
     *
     * @return int
     */
    public function linea(): int
    {
        return $this->linea;
    }

    /**
     * Mensaje de error
     *
     * @return string
     */
    public function mensaje(): string
    {
        return $this->mensaje;
    }

    /**
     * Ruta del archivo
     *
     * @return string
     */
    public function archivo(): string
    {
        return $this->archivo;
    }

}
