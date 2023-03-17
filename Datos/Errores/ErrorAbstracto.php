<?php

namespace Gof\Datos\Errores;

use Gof\Interfaz\Errores\Errores;

/**
 * Clase abstracta para los conjuntos de errores
 *
 * Clase abstracta que implementa la interfaz **Gof\Interfaz\Errores\Errores** y proporciona
 * las funcionalidades básicas. La agregación de los errores se delega en la clases hijas.
 *
 * @package Gof\Datos\Errores
 */
abstract class ErrorAbstracto implements Errores
{
    /**
     * @var array<string, int> Pila de errores
     */
    protected $errores = [];

    public function __construct(array $listaDeErrores = [])
    {
        $this->errores = $listaDeErrores;
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
     * Lista de errores
     *
     * @return int[] Devuelve la pila de errores
     */
    public function lista(): array
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
