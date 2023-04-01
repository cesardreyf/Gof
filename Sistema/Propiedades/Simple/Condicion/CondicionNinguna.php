<?php

namespace Gof\Sistema\Propiedades\Simple\Condicion;

use Gof\Sistema\Propiedades\Simple\Interfaz\Condicion;

/**
 * Condición por defecto del operador condicional del sistema de propiedades
 *
 * @package Gof\Sistema\Propiedades\Simple\Condicion
 */
class CondicionNinguna implements Condicion
{

    /**
     * Condicion que siempre devuelve false
     *
     * @return bool Devuelve **false**.
     */
    public function condicion(): bool
    {
        return false;
    }

}
