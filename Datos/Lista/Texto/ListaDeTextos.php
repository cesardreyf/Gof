<?php

namespace Gof\Datos\Lista\Texto;

use Exception;
use Gof\Interfaz\Lista\Textos;

/**
 * Tipo de datos para listas de textos
 *
 * Clase encargada de garantizar que todos los elementos almacenados en la lista son textos (string).
 *
 * @package Gof\Datos\Lista\Texto
 */
class ListaDeTextos implements Textos
{
    /**
     * @var string[] Lista de textos
     */
    private $lista = [];

    /**
     * Constructor
     *
     * @param string[] $lista Lista de textos
     */
    public function __construct(array $lista = [])
    {
        foreach( $lista as $elemento ) {
            if( !is_string($elemento) ) {
                throw new Exception('Existe uno o más elementos que no son son string');
            }

            $this->agregar($elemento);
        }
    }

    /**
     * Agrega un nuevo elemento a la lista
     *
     * @param string $texto Cadena de caracteres a ser agregados a la lista
     *
     * @return string Devuelve la misma cadena pasada por argumento
     */
    public function agregar(string $texto): string
    {
        return $this->lista[] = $texto;
    }

    /**
     * Obtiene la lista completa de textos
     *
     * @return string[] Devuelve la lista interna de textos
     */
    public function lista(): array
    {
        return $this->lista;
    }

}
