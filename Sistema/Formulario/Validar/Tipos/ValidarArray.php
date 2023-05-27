<?php

namespace Gof\Sistema\Formulario\Validar\Tipos;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Valida que el valor de un campo sea un array
 *
 * @package Gof\Sistema\Formulario\Validar\Tipos
 */
abstract class ValidarArray
{

    /**
     * Verifica si el valor del campo es un array válido
     *
     * Comprueba que el valor del campo sea un tipo de dato **array**.
     *
     * @param Campo $campo Instancia del campo a validar.
     *
     * @return bool Devuelve **true** si es un array válido o **false** de lo contrario.
     */
    static public function validar(Campo $campo): bool
    {
        if( is_array($campo->valor()) ) {
            return true;
        }

        Error::reportar($campo, ErroresMensaje::NO_ES_ARRAY, Errores::ERROR_NO_ES_ARRAY);
        return false;
    }

}
