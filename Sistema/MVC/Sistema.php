<?php

namespace Gof\Sistema\MVC;

use Gof\Sistema\MVC\Registros\Registros;

/**
 * Sistema MVC
 *
 * Conjunto de herramientas que ofrecen un servicio capaz de ejecutar y mantener
 * una aplicación web para la arquitectura MVC (Modelo-Vista-Controlador).
 *
 * @package Gof\Sistema\MVC
 */
class Sistema
{
    /**
     * @var Registros Instancia del gestor de registros de errores
     */
    private Registros $registros;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registros = new Registros();
    }

    /**
     * Obtiene el gestor de registros de errores
     *
     * @return Registros
     */
    public function registros(): Registros
    {
        return $this->registros;
    }

}
