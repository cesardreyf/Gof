<?php

namespace Gof\Sistema\MVC\Datos;

/**
 * Datos de Acceso Público del Sistema MVC
 *
 * Datos compartidos entre gestores del sistema MVC.
 *
 * @package Gof\Sistema\MVC\Datos
 */
class DAP
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

    /**
     * Conjunto de argumentos para pasar al constructor del controlador
     *
     * Almacena en un array los argumentos que serán pasados
     * al controlador al momento de instanciarlo.
     *
     * @var array
     */
    public array $argumentos = [];
}
