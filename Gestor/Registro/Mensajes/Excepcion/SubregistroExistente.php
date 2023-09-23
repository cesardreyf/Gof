<?php

namespace Gof\Gestor\Registro\Mensajes\Excepcion;

/**
 * Excepción lanzado cuando se intenta crear un subregistro con un nombre ya existente
 *
 * @package Gof\Gestor\Registro\Mensajes\Excepcion
 */
class SubregistroExistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombreDelSubregistro
     */
    public function __construct(string $nombreDelSubregistro)
    {
        parent::__construct("El subregistro {$nombreDelSubregistro} ya existe");
    }

}
