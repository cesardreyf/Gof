<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

/**
 * Interfaz para los gestores encargados de imprimir errores
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface ErrorImprimible
{
    /**
     * Imprime la información del error
     *
     * @param Error $error Instancia del error a ser mostrado.
     */
    public function imprimir(Error $error);
}
