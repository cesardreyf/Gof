<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

class TipoFloat extends Campo
{

    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_FLOAT);
    }

    public function validar(): ?bool
    {
        if( is_float($this->valor()) ) {
            return true;
        }

        if( is_string($this->valor()) ) {
            if( empty(trim($this->valor())) ) {
                Error::reportar($this, ErroresMensaje::CAMPO_VACIO, Errores::ERROR_CAMPO_VACIO);
                return false;
            }

            if( filter_var($this->valor(), FILTER_VALIDATE_FLOAT) !== false ) {
                return true;
            }
        }

        Error::reportar($this, ErroresMensaje::NO_ES_FLOAT, Errores::ERROR_NO_ES_FLOAT);
        return false;
    }

}
