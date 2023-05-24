<?php

namespace Gof\Sistema\Formulario\Mediador\Campo;

use Gof\Interfaz\Formulario\Campo;

/**
 * Mediador para los errores de campos
 *
 * Clase encargada de escribir los mensajes y código de errores en los campos.
 *
 * @package Gof\Sistema\Formulario\Mediador\Campo
 */
abstract class Error
{

    /**
     * Genera un error dentro del campo
     *
     * Agrega un mensaje y un código de error al campo.
     *
     * @param Campo  $campo   Instancia del campo donde se generará el error.
     * @param string $mensaje Mensaje de error.
     * @param int    $codigo  Código de error.
     */
    static public function reportar(Campo $campo, string $mensaje, int $codigo)
    {
        $campo->error()->codigo($codigo);
        $campo->error()->mensaje($mensaje);
    }

}
