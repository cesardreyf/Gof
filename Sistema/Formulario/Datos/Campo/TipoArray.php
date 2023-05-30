<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Campo de tipo array
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
class TipoArray extends Campo
{

    /**
     * Constructor
     *
     * @param string $clave Nombre del campo
     */
    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_ARRAY);
    }

    /**
     * Valida que el valor del campo sea un array válido
     *
     * @return ?bool Si es válido devuelve **true**, caso contrario **false**.
     */
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
