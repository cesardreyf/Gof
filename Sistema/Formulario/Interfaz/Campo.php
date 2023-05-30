<?php

namespace Gof\Sistema\Formulario\Interfaz;

use Gof\Interfaz\Errores\Mensajes\Error;

/**
 * Interfaz para los campos de los formularios
 *
 * Interfaz para acceder a los campos del formulario sin necesidad de conocer
 * la implementación.
 *
 * @package Gof\Sistema\Formulario\Interfaz
 */
interface Campo
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
     * Valida el tipo de dato del campo
     *
     * @return ?bool Devuelve **true** si es válido o **false** de lo contrario; **null** en caso de errores.
     */
    public function validar(): ?bool;
}
