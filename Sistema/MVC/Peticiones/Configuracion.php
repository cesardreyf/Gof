<?php

namespace Gof\Sistema\MVC\Peticiones;

use Gof\Contrato\Peticiones\Peticiones;

/**
 * Datos de configuración para el módulo de peticiones del sistema mvc
 *
 * @package Gof\Sistema\MVC\Peticiones
 */
abstract class Configuracion
{
    /**
     * Clave del método GET desde donde se obtendrá la solicitud
     *
     * @var string
     */
    public string $url;

    /**
     * Instancia del gestor de peticiones
     *
     * Gestor encargado de procesar la solicitud y crear la lista de recursos
     * solicitados
     *
     * @var Peticiones
     */
    public Peticiones $gestor;
}
