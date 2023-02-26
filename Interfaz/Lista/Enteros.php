<?php

namespace Gof\Interfaz\Lista;

use Gof\Interfaz\Lista;

/**
 * Interfaz para clases que represente un conjunto de números enteros
 *
 * @package Gof\Interfaz\Lista
 */
interface Enteros extends Lista
{
    /**
     * Agrega un nuevo elemento al conjunto de números enteros
     *
     * @param int $elemento Número entero
     *
     * @return int Devuelve el mismo valor agregado
     */
    public function agregar(int $elemento): int;
}
