<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

class TipoInt extends Campo
{

    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_INT);
    }

    public function validar(): ?bool
    {
        if( $this->error()->hay() ) {
            return null;
        }

        if( is_int($this->valor()) ) {
            return true;
        }

        if( is_string($this->valor()) && preg_match('/^-?[0-9]+$/', trim($this->valor())) === 1 ) {
            return true;
        }

        Error::reportar($this, ErroresMensaje::NO_ES_INT, Errores::ERROR_NO_ES_INT);
        return false;
    }

}
