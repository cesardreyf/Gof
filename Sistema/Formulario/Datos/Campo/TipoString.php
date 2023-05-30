<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Campo de tipo string
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
class TipoString extends Campo
{

    /**
     * Constructor
     *
     * @param string $clave Nombre del campo.
     */
    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_STRING);
    }

    /**
     * Valida que el valor del campo sea un string válido
     *
     * @return ?bool Si es válido devuelve **true**, caso contrario **false**.
     */
    public function validar(): ?bool
    {
        if( $this->error()->hay() ) {
            return null;
        }

        if( is_string($this->valor()) === false ) {
            Error::reportar($this, ErroresMensaje::NO_ES_STRING, Errores::ERROR_NO_ES_STRING);
            return false;
        }

        return true;
    }

}
