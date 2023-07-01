<?php

namespace Gof\Sistema\MVC\Aplicacion\Interfaz;

/**
 * Contrato para los controladores
 *
 * @package Gof\Sistema\MVC\Aplicacion\Interfaz
 */
interface Controlador
{
    /**
     * Establece los parámetros del controlador
     *
     * @param array $parametros Lista de parámetros.
     */
    public function parametros(array $parametros);
}
