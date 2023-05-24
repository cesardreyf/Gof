<?php

namespace Gof\Sistema\Formulario\Validar\Tipos;

use Gof\Interfaz\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

abstract class ValidarInt
{

    static public function validar(Campo $campo): bool
    {
        if( is_int($campo->valor()) ) {
            return true;
        }

        if( is_string($campo->valor()) && preg_match('/^-?[0-9]+$/', $campo->valor()) === 1 ) {
            return true;
        }

        Error::reportar($campo, ErroresMensaje::NO_ES_INT, Errores::ERROR_NO_ES_INT);
        return false;
    }

}
