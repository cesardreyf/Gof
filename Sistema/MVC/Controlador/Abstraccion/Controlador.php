<?php

namespace Gof\Sistema\MVC\Controlador\Abstraccion;

use Gof\Sistema\MVC\Controlador\Interfaz\Controlador as IControlador;

/**
 * Abstracción para los controladores
 *
 * @package Gof\Sistema\MVC\Controlador\Abstraccion
 */
abstract class Controlador implements IControlador
{
    /**
     * Almacena los parámetros
     *
     * @param array
     */
    private array $parametros;

    /**
     * Define los parámetros del controlador
     *
     * @param array $parametros
     */
    /*final*/ public function parametros(array $parametros)
    {
        $this->parametros = $parametros;
    }

}
