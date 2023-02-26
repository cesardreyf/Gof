<?php

namespace Gof\Interfaz\Enrutador;

/**
 * Interfaz para clases que devuelvan el nombre de una clase válido para instanciar
 *
 * @package Gof\Interfaz\Enrutador
 */
interface Enrutador
{
    /**
     * Devuelve el nombre completo de la clase
     *
     * Obtiene el nombre de la clase y su respectivo namespace en base al criterio
     * de la implementación de la interfaz Enrutador.
     *
     * @return string Devulve el nombre de la clase junto a su namespace
     */
    public function nombreClase(): string;
}
