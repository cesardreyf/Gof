<?php

namespace Gof\Sistema\MVC\Registros;

use Gof\Sistema\MVC\Registros\Modulo\Operacion;

/**
 * Gestor de registros de errores
 *
 * Clase encargada de gestionar los errores producidos por PHP.
 *
 * @package Gof\Sistema\MVC\Registros
 */
class Registros
{
    /**
     * @var Errores Gestor de errores
     */
    private Errores $errores;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->errores = new Errores();
    }

    /**
     * Obtiene el gestor de errores
     *
     * @return Errores
     */
    public function errores(): Errores
    {
        return $this->errores;
    }

}
