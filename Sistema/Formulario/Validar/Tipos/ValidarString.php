<?php

namespace Gof\Sistema\Formulario\Validar\Tipos;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Valida que el valor de un campo sea un string
 *
 * @package Gof\Sistema\Formulario\Validar\Tipos
 */
abstract class ValidarString
{

    /**
     * Verifica si el valor del campo es un string válido
     *
     * Comprueba que el valor del campo sea un tipo de dato **string**.
     *
     * @param Campo $campo Instancia del campo a validar.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    static public function validar(Campo $campo): bool
    {
        if( is_string($campo->valor()) === false ) {
            Error::reportar($campo, ErroresMensaje::NO_ES_STRING, Errores::ERROR_NO_ES_STRING);
            return false;
        }

        return true;
    }

}
