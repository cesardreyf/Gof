<?php

namespace Gof\Interfaz\Lista;

use Gof\Interfaz\Lista;

/**
 * Interfaz para conjuntos de textos
 *
 * @package Gof\Interfaz\Lista
 */
interface Textos extends Lista
{
    /**
     * Agrega un nuevo elemento al conjunto
     *
     * @param string $elemento Texto a ser agregado
     *
     * @return string Devuelve el mismo valor agregado
     */
    public function agregar(string $elemento): string;
}
