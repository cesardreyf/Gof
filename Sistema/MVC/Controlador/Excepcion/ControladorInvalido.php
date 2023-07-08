<?php

namespace Gof\Sistema\MVC\Controlador\Excepcion;

/**
 * Excepción lanzada cuando el controlador no es válido
 * 
 * Excepción lanzada cuando el controlador instanciado no implementa la interfaz esperada
 *
 * @package Gof\Sistema\MVC\Controlador\Excepcion
 */
class ControladorInvalido extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $controlador Nombre de la clase controlador
     * @param string $interfaz    Nombre de la interfaz esperada
     */
    public function __construct(string $controlador, string $interfaz)
    {
        parent::__construct("La clase '{$controlador}' no implementa la interfaz {$interfaz}");
    }

}
