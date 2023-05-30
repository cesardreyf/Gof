<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Campo de tipo int
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
class TipoInt extends Campo
{

    /**
     * Constructor
     *
     * @param string $clave Nombre del campo.
     */
    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_INT);
    }

    /**
     * Valida que el valor del campo sea un int válido
     *
     * Admite valores enteros y string numéricos.
     *
     * @return ?bool Si es válido devuelve **true**, caso contrario **false**.
     */
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
