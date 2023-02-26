<?php

namespace Gof\Datos\Errores;

use Gof\Interfaz\Errores\Errores;

/**
 * Tipo de datos para pilas de errores con valores numéricos
 *
 * @package Gof\Datos\Errores
 */
class ErrorNumerico implements Errores
{
    /**
     * @var int[] Pila de errores
     */
    private $errores = [];

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

    /**
     * Especifica si hay errores en la pila
     *
     * @return bool Devuelve **true** si hay errores o **false** de lo contrario
     */
    public function hay(): bool
    {
        return !empty($this->errores);
    }

    /**
     * Obtiene el último error ocurrido
     *
     * Por cada llamada se obtiene el último error y se elimina de la pila.
     * Si no hay errores en la pila se devuelve cero.
     *
     * @return int Devuelve el último error o cero si la pila está vacía
     */
    public function error(): int
    {
        if( empty($this->errores) ) {
            return 0;
        }

        return array_pop($this->errores);
    }

    /**
     * Pila de errores
     *
     * @return int[] Devuelve la pila de errores
     */
    public function errores(): array
    {
        return $this->errores;
    }

    /**
     * Limpia la pila de errores
     */
    public function limpiar()
    {
        $this->errores = [];
    }

}
