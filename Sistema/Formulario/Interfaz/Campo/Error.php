<?php

namespace Gof\Sistema\Formulario\Interfaz\Campo;

use Gof\Interfaz\Errores\Mensajes\Error as IError;

/**
 * Interfaz para acceder a los errores de los campos
 *
 * Proporciona acceso a los errores de los campos del formulario. Se puede
 * obtener tanto el mensaje de error como una lista de errores. Esto último lo
 * determina el propio campo o el gestor de errores.
 *
 * @package Gof\Sistema\Formulario\Interfaz\Campo
 */
interface Error extends IError
{
    /**
     * Obtiene el o los errores del campo
     *
     * Obtiene el mensaje de error o una lista (array) con los errores del
     * campo.
     *
     * @return string|array Devuelve los errores del campo.
     */
    public function obtener(): mixed;
}
