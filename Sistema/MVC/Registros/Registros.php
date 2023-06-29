<?php

namespace Gof\Sistema\MVC\Registros;

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
     * @var Excepciones Gestor de excepciones
     */
    private Excepciones $excepciones;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->errores = new Errores();
        $this->excepciones = new Excepciones();
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

    /**
     * Obtiene el gestor de errores
     *
     * @return Excepciones
     */
    public function excepciones(): Excepciones
    {
        return $this->excepciones;
    }

}
