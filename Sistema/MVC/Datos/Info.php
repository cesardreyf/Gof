<?php

namespace Gof\Sistema\MVC\Datos;

/**
 * Datos compartidos del Sistema MVC
 *
 * Datos compartidos entre gestores del sistema MVC.
 *
 * @package Gof\Sistema\MVC\Datos
 */
class Info
{
    /**
     * Nombre completo del controlador
     *
     * Nombre de la clase junto con su espacio de nombre.
     *
     * @var string
     */
    public string $controlador = '';

    /**
     * Lista de elementos que serán considerados como parámetros para el controlador
     *
     * @var array
     */
    public array $parametros = [];
}
