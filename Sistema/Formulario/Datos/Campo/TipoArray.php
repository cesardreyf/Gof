<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

class TipoArray extends Campo
{

    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_ARRAY);
    }

    public function validar(): ?bool
    {
        if( $this->error->hay() ) {
            return null;
        }

        if( is_array($this->valor()) ) {
            return true;
        }

        Error::reportar($this, ErroresMensaje::NO_ES_ARRAY, Errores::ERROR_NO_ES_ARRAY);
        return false;
    }

}
