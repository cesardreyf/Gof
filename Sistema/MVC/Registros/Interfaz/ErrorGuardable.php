<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

/**
 * Interfaz para los gestores encargados de guardar errores
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface ErrorGuardable
{
    /**
     * Guarda la información del error
     *
     * @param Error $error Instancia del error a ser guardado.
     *
     * @return bool Devuelve **true** si se guardó correctamente o **false** de lo contrario
     */
    public function guardar(Error $error): bool;
}
