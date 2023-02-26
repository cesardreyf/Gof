<?php

namespace Gof\Datos\Lista\Numero;

use Exception;
use Gof\Interfaz\Lista\Enteros;

/**
 * Tipo de datos para listas de números enteros
 *
 * Clase encargada de garantizar que los datos almacenados sean números enteros
 *
 * @package Gof\Datos\Lista\Numero
 */
class ListaDeEnteros implements Enteros
{
    /**
     * @var array Lista interna
     */
    private $lista = [];

    /**
     * Constructor
     *
     * @param array $lista Lista cuyos elementos sean valores enteros (int)
     */
    public function __construct(array $lista = [])
    {
        $this->lista = $lista;

        foreach( $lista as $elemento ) {
            if( !is_int($elemento) ) {
                throw new Exception('Existe uno o más elementos que no son números enteros');
            }
        }
    }

    /**
     * Agrega un número entero a la lista interna
     *
     * @param int $numero Número entero a ser agregado
     *
     * @return int Devuelve el mismo valor agregado
     */
    public function agregar(int $numero): int
    {
        return $this->lista[] = $numero;
    }

    /**
     * Lista del conjunto de números enteros almacenados
     *
     * @return int[] Devuelve la lista de valores almacenados
     */
    public function lista(): array
    {
        return $this->lista;
    }

}
