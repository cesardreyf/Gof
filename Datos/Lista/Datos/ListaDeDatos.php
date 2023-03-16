<?php

namespace Gof\Datos\Lista\Datos;

use Gof\Interfaz\Lista\Datos;

/**
 * Componente de datos mixtos
 *
 * Clase encargada de almacenar un conjunto de datos.
 *
 * @package Gof\Datos\Lista\Datos
 */
class ListaDeDatos implements Datos
{
    /**
     * @var array<string, mixed> Lista de datos
     */
    private $datos = [];

    /**
     * Agrega un nuevo dato a la lista
     *
     * Agrega a la lista de datos un nuevo elemento con el dato especificado. Si ya existe
     * un elemento en la lista cuyo identificador son idénticos se devolverá **null**. Para
     * cambiar el valor de un elemento ya existente use la función **cambiar()**.
     *
     * Si no se especifica ninguna clave asociada el dato agregado se apilará en el array
     * interno y se devolverá un **string** con el identificador.
     *
     * @param mixed   $datos Dato a ser almacenado.
     * @param ?string $clave Clave con la cual se asociará al dato en la lista interna.
     *
     * @return ?string Devuelve el identificador asociado al dato o **NULL** en caso de error.
     *
     * @see ListaDeDatos::cambiar() para cambiar el valor de un elemento ya existente.
     */
    public function agregar($dato, ?string $clave = null): ?string
    {
        if( $clave === null ) {
            $id = (string)count($this->datos);
            $this->datos[] = $dato;
            return $id;
        }

        if( isset($this->datos[$clave]) ) {
            return null;
        }

        $this->datos[$clave] = $dato;
        return $clave;
    }

    /**
     * Elimina un elemento de la lista
     *
     * Quita de la lista un dato asociado a un identificador.
     *
     * @param string $identificador Clave asociada al dato.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function remover(string $identificador): bool
    {
        if( isset($this->datos[$identificador]) ) {
            unset($this->datos[$identificador]);
            return true;
        }

        return false;
    }

    /**
     * Cambia el valor de un elemento de la lista
     *
     * Modifica el valor de un elemento existente de la lista. Si no existe ningún elemento
     * en la lista con el identificador proporcionado se devolverá **false**.
     *
     * @param string $identificador Clave asociada al elemento a ser cambiado.
     * @param mixed  $dato          Nuevo valor por el que se reemplazará.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function cambiar(string $identificador, $dato): bool
    {
        if( isset($this->datos[$identificador]) ) {
            $this->datos[$identificador] = $dato;
            return true;
        }

        return false;
    }

    /**
     * Obtiene un elemento de la lista
     *
     * Obtiene el dato asociado al identificador, siempre y cuando este exista.
     *
     * @param string $identificador Clave asociada al dato que se quiere obtener.
     *
     * @return mixed Devuelve el dato asociado al identificador si existe o **NULL** de lo contrario.
     */
    public function obtener(string $identificador)
    {
        if( isset($this->datos[$identificador]) ) {
            return $this->datos[$identificador];
        }

        return null;
    }

    /**
     * Devuelve el conjunto de datos almacenados internamente.
     *
     * @return array<string, mixed> Devuelve un array con la lista de datos.
     */
    public function lista(): array
    {
        return $this->datos;
    }

}
