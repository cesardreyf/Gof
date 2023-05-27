<?php

namespace Gof\Sistema\Formulario\Validar\Tipos;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Valida que el valor de un campo sea un int
 *
 * @package Gof\Sistema\Formulario\Validar\Tipos
 */
abstract class ValidarInt
{

    /**
     * Verifica si el valor del campo es un int válido
     *
     * Comprueba que el valor del campo sea un tipo de dato **int**. Si no lo es verifica
     * si es una cadena numérica con un valor entero.
     *
     * @param Campo $campo Instancia del campo a validar.
     *
     * @return bool Devuelve **true** si es un int válido o **false** de lo contrario.
     */
    static public function validar(Campo $campo): bool
    {
        if( is_int($campo->valor()) ) {
            return true;
        }

        if( is_string($campo->valor()) && preg_match('/^-?[0-9]+$/', trim($campo->valor())) === 1 ) {
            return true;
        }

        Error::reportar($campo, ErroresMensaje::NO_ES_INT, Errores::ERROR_NO_ES_INT);
        return false;
    }

}
