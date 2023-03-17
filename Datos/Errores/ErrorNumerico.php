<?php

namespace Gof\Datos\Errores;

/**
 * Tipo de datos para pilas de errores con valores numéricos
 *
 * @package Gof\Datos\Errores
 */
class ErrorNumerico extends ErrorAbstracto
{

    /**
     * Agrega un error a la pila de errores
     *
     * @param int $error Identificador del error
     *
     * @return int Devuelve el mismo valor recibido por parámetro
     */
    public function agregar(int $error): int
    {
        return $this->errores[] = $error;
    }

}
