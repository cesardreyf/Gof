<?php

namespace Gof\Sistema\Formulario\Validar\Tipos;

use Gof\Interfaz\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

abstract class ValidarString
{

    static public function validar(Campo $campo): bool
    {
        if( is_string($campo->valor()) === false ) {
            Error::reportar($campo, ErroresMensaje::NO_ES_STRING, Errores::ERROR_NO_ES_STRING);
            return false;
        }

        return true;
    }

}
