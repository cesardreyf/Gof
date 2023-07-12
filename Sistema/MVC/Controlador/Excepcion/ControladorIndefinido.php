<?php

namespace Gof\Sistema\MVC\Controlador\Excepcion;

/**
 * Excepción lanzada cuando no se definió ningún controlador
 * 
 * Excepción lanzada al intentar hacer uso del controlador pero este está
 * indefinido.
 *
 * @package Gof\Sistema\MVC\Controlador\Excepcion
 */
class ControladorIndefinido extends Excepcion
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('No se definió ningún controlador');
    }

}
