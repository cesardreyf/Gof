<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\Tipos\ValidarArray;
use Gof\Sistema\Formulario\Validar\Tipos\ValidarInt;
use Gof\Sistema\Formulario\Validar\Tipos\ValidarString;

/**
 * Valida un campo según su tipo de dato especificado
 *
 * Verifica que el valor de un campo corresponda con un tipo predeterminado.
 *
 * @package Gof\Sistema\Formulario\Validar
 */
class ValidarTipos implements ErroresMensaje
{
    /**
     * @var ?bool Almacena el estado de la validación del campo.
     */
    private ?bool $valido = null;

    /**
     * Constructor
     *
     * La validación solo se hace si no existen errores previos en el campo.
     *
     * @param Campo $campo Campo a validar.
     */
    public function __construct(Campo $campo)
    {
        if( $campo->error()->hay() === false ) {
            $this->valido = $this->tipoDelCampoEsValido($campo);
        }
    }

    /**
     * Verifica si el tipo es el correcto para el campo
     *
     * En función del tipo seleccionado se hace una comprobación. Si el tipo del valor del
     * campo corresponde con el tipo seleccionado devuelve **true**, caso contrario **false**.
     *
     * @param Campo $campo Campo a validar.
     *
     * @return bool Devuelve **true** si corresponde con el valor del campo o **false** de lo contrario.
     */
    public function tipoDelCampoEsValido(Campo $campo): bool
    {
        switch( $campo->tipo() ) {
            case Tipos::TIPO_STRING:
                return ValidarString::validar($campo);

            case Tipos::TIPO_INT:
                return ValidarInt::validar($campo);

            case Tipos::TIPO_ARRAY:
                return ValidarArray::validar($campo);
        }
    }

    /**
     * Devuelve un valor bool o null dependiendo de la validación
     *
     * Si existen errores previos en el campo no se procede a ninguna
     * validación y se devuelve **null**, caso contrario se hace la validación
     * y el resultado será un **bool** y dependerá de la validación.
     *
     * @return ?bool
     */
    public function valido(): ?bool
    {
        return $this->valido;
    }

}
