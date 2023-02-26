<?php

namespace Gof\Gestor\Autoload\Interfaz;

/**
 * Interfaz para un cargador de archivos
 *
 * Interfaz empleada por el gestor de autoload para cargar los archivos.
 *
 * @package Gof\Gestor\Autoload\Interfaz
 */
interface Cargador
{
    /**
     * Obtiene el último error ocurrido
     *
     * @return int Devuelve el último error ocurrido durante la carga de un archivo
     */
    public function error(): int;

    /**
     * Carga un archivo
     *
     * @param string $ruta Ubicación del archivo
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function cargar(string $ruta): bool;
}
