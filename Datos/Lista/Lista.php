<?php

namespace Gof\Datos\Lista;

use Gof\Interfaz\Lista as ILista;

/**
 * Tipo de datos para listas genéricas
 *
 * Lista simple e inutil. Se está abstraendo el tipo de datos **array** así que probablemente
 * lo elimine más adelante. No recuerdo por qué lo hice así que de momento se queda aquí quieto.
 *
 * @package Gof\Datos\Lista
 */
class Lista implements ILista
{
    /**
     * @var array Lista interna
     */
    private $lista = [];

    /**
     * Constructor
     *
     * @param array $lista Lista de elementos
     */
    public function __construct(array $lista)
    {
        $this->lista = $lista;
    }

    /**
     * @return array Devuelve el array interno
     */
    public function lista(): array
    {
        return $this->lista;
    }

}
