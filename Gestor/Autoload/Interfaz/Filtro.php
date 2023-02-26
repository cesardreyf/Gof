<?php

namespace Gof\Gestor\Autoload\Interfaz;

/**
 * Interfaz empleada por el gestor de autoload para los filtros de cadenas
 *
 * @package Gof\Gestor\Autoload\Interfaz
 */
interface Filtro
{
    /**
     * Indica si la cadena es válida para el nombre de una clase, interfaz o trait
     *
     * @param string $cadena Cadena a validar
     *
     * @return bool Devuelve **true** si la cadena es válida o **false** de lo contrario
     */
    public function clase(string $cadena): bool;

    /**
     * Indica si la cadena es válida para un espacio de nombre
     *
     * @param string $cadena Cadena a validar
     *
     * @return bool Devuelve **true** si la cadena es válida o **false** de lo contrario
     */
    public function espacioDeNombre(string $cadena): bool;
}
