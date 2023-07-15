<?php

namespace Gof\Sistema\MVC\Aplicacion\DAP;

/**
 * Datos de Acceso Público del Sistema MVC
 *
 * Datos compartidos entre gestores del sistema MVC.
 *
 * @package Gof\Sistema\MVC\Aplicacion\DAP
 */
class N1 implements DAP
{
    /**
     * Nombre de la clase del controlador
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
