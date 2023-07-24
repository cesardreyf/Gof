<?php

namespace Gof\Sistema\MVC\Aplicacion\DAP\Solicitud;

/**
 * Almacena datos de la solicitud
 *
 * @package Gof\Sistema\MVC\Aplicacion\DAP\Solicitud
 */
class Solicitud
{
    /**
     * Cadena de la solicitud
     *
     * @var string
     */
    public string $cadena = '';

    /**
     * Método empleado para la solicitud
     *
     * @var Metodo
     */
    public Metodo $metodo;

    /**
     * Lista de solicitud
     *
     * Lista con la solicitud dividida en elementos de un array.
     *
     * @var array
     */
    public array $lista = [];
}
