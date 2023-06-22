<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Campo de tipo bool
 *
 * Campo de tipo bool para asociar un checkbox de un formulario.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
class TipoBool extends Campo
{
    /**
     * @var string[] Palabras que se consideran válidas para un **true**.
     */
    public const PALABRAS_ACEPTADAS_TRUE = ['on', 'si', '1', ''];

    /**
     * @var string[] Palabras que se consideran válidas para un **false**.
     */
    public const PALABRAS_ACEPTADAS_FALSE = ['off', 'no', '0'];

    /**
     * @var string Mensaje de error para cuando el valor del campo no sea el esperado.
     */
    public const VALOR_INCORRECTO = 'El valor del campo no es correcto';

    /**
     * Constructor
     *
     * @param string $clave Nombre del campo.
     */
    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_BOOL);
    }

    /**
     * Valida que el valor del campo corresponda con un checkbox
     *
     * Si el valor del campo es un **bool** o un **string** con palabras válidas la
     * validación devolverá **true**, caso contrario devolverá **false** y se escribirá
     * un código y un mensaje de error en el campo.
     *
     * @return ?bool Devuelve el estado de la validación.
     *
     * @see TipoBool::PALABRAS_ACEPTADAS_TRUE
     * @see TipoBool::PALABRAS_ACEPTADAS_FALSE
     */
    public function validar(): ?bool
    {
        if( is_bool($this->valor()) ) {
            return true;
        }

        if( is_string($this->valor()) ) {
            if( strlen($this->valor) < 4 ) {
                $valor = strtolower($this->valor());

                if( in_array($valor, self::PALABRAS_ACEPTADAS_TRUE) ) {
                    $this->valor = true;
                    return true;
                }

                if( in_array($valor, self::PALABRAS_ACEPTADAS_FALSE) ) {
                    $this->valor = false;
                    return true;
                }
            }
        }

        Error::reportar(
            $this,
            self::VALOR_INCORRECTO,
            Errores::ERROR_NO_ES_BOOL
        );
        return false;
    }

}
