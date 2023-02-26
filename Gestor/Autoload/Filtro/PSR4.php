<?php

namespace Gof\Gestor\Autoload\Filtro;

use Gof\Gestor\Autoload\Interfaz\Filtro;

/**
 * Filtro para el gestor de autoload
 *
 * Filtro para solo aceptar aquellos nombres válidos para un nombre de una clase, interfaz o trait.
 *
 * @package Gof\Gestor\Autoload\Filtro
 */
class PSR4 implements Filtro
{
    // const EXPRESION_REGULAR_PARA_NAMESPACE = '/^(\w+|)$/';
    // const EXPRESION_REGULAR_PARA_CLASES = '/^\\?\\?[a-zA-Z]\w*(\\?\\?[a-zA-Z]\w*|)+$/';

    /**
     * @var string Expresión regular para las clases, interfaz o trait
     */
    const EXPRESION_REGULAR_PARA_CLASES    = '/^(\\\\{0,1}[a-zA-Z]\w*)+$/';

    /**
     * @var string Expresión regular para los espacios de nombre
     */
    const EXPRESION_REGULAR_PARA_NAMESPACE = '/^([a-zA-Z]\w*)?$/';

    /**
     * Valida que la cadena sea un nombre válido para una clase, interfaz o trait
     *
     * La clase puede empezar con una barra invertida y le debe seguir el nombre de la clase.
     * Esta tiene que empezar con un caracter alafabético (ASCII) y luego cualquier caracter alfanumérico (ASCII)
     * o guión bajo (_). Este se puede repetir cuantas veces se quiera.
     * La cadena no puede terminar con una barra invertida.
     *
     * Las barras invertidas pueden ser dobles.
     *
     * @param string $cadena Nombre de la clase a filtrar
     *
     * @return bool Devuelve **true** si cumple con los requisitos o **false** de lo contrario
     */
    public function clase(string $cadena): bool
    {
        return preg_match(self::EXPRESION_REGULAR_PARA_CLASES, $cadena) === 1;
    }

    /**
     * Valida si la cadena es un espacio de nombre válido
     *
     * Solo se permiten caracteres alfanuméricos (ASCII) y guión bajo (_). 
     * El primer caracter debe ser siempre un caracter alfabético.
     *
     * También es posible reservar un espacio de nombre "nulo", osea igual a ''.
     * Esto es para un espacio de nombre global.
     *
     * @param string $cadena Cadena con el espacio de nombre a validar
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function espacioDeNombre(string $cadena): bool
    {
        return preg_match(self::EXPRESION_REGULAR_PARA_NAMESPACE, $cadena) === 1;
    }

}
