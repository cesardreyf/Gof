<?php

namespace Gof\Interfaz;

/**
 * Interfaz genérica para listas
 *
 * @package Gof\Interfaz
 */
interface Lista
{
    /**
     * Devuelve un conjunto de datos
     *
     * @return array Devuelve un conjunto de datos
     */
    public function lista(): array;
}
