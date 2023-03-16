<?php

namespace Gof\Interfaz\Lista;

use Gof\Interfaz\Lista;

/**
 * Interfaz para conjuntos de datos mixtos (sin discriminación de tipos)
 *
 * @package Gof\Interfaz\Lista
 */
interface Datos extends Lista
{
    /**
     * Agrega un dato a la lista
     *
     * @param mixed   $dato  Dato a ser agregado.
     * @param ?string $clave Clave asociativa o **NULL** para apilar al final de la lista.
     *
     * @return ?string Devuelve el identificador asociado al nuevo dato en la lista o **NULL** en caso de error.
     */
    public function agregar($dato, ?string $clave = null): ?string;

    /**
     * Remueve un dato de la lista
     *
     * @param string $identificador Clave asociada al dato a ser eliminado.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function remover(string $identificador): bool;

    /**
     * Cambia el valor de un dato ya existente
     *
     * @param string $id        Clave asociada al viejo dato.
     * @param mixed  $nuevoDato Nuevo valor a ser cambiado por el viejo.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function cambiar(string $id, $nuevoDato): bool;

    /**
     * Obtiene un dato de la lista
     *
     * @param string $identificado Clave asociada al dato a ser obtenido.
     *
     * @return mixed Devuelve el valor almacenado en la lista si existe o **NULL** de lo contrario.
     */
    public function obtener(string $identificador);
}
