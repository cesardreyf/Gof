<?php

namespace Gof\Sistema\Propiedades\Simple\Interfaz;

/**
 * Interfaz para las condiciones de los operadores condicionales
 *
 * @package Gof\Sistema\Propiedades\Simple\Interfaz
 */
interface Condicion
{
    /**
     * Valida la condicion
     *
     * @return bool Devuelve **true** si se cumple la condición o **false** de lo contrario.
     */
    public function condicion(): bool;
}
