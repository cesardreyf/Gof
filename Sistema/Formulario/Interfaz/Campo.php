<?php

namespace Gof\Sistema\Formulario\Interfaz;

use Gof\Interfaz\Errores\Mensajes\Error;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;

/**
 * Interfaz para los campos de los formularios
 *
 * Interfaz para acceder a los campos del formulario sin necesidad de conocer
 * la implementación.
 *
 * @package Gof\Sistema\Formulario\Interfaz
 */
interface Campo extends Validable
{
    /**
     * Tipo del campo
     *
     * @return int Devuelve el tipo de dato del campo.
     */
    public function tipo(): int;

    /**
     * Lista de errores internas
     *
     * Almacena los errores asociados al campo.
     *
     * @return Error Devuelve una instancia del gestor de errores del campo.
     */
    public function error(): Error;

    /**
     * Valor del campo
     *
     * @return mixed Devuelve el valor del campo.
     */
    public function valor(): mixed;

    /**
     * Nombre del campo
     *
     * @return string Devuelve el nombre del campo.
     */
    public function clave(): string;

    /**
     * Obtiene el estado de obligatoriedad del campo
     *
     * @return bool Devuelve **true** si el campo es obligatorio o **false** si es opcional.
     */
    public function obligatorio(): bool;
}
