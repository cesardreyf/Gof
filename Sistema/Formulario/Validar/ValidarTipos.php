<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Interfaz\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;

/**
 * Valida un campo según su tipo de dato especificado
 *
 * Verifica que el valor de un campo corresponda con un tipo predeterminado.
 *
 * @package Gof\Sistema\Formulario\Validar
 */
class ValidarTipos
{
    public const NO_ES_STRING = 'Se esperaba un valor de tipo string';
    public const NO_ES_INT = 'Se esperaba un valor de tipo numérico';

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
                return $this->validarCadena($campo);

            case Tipos::TIPO_INT:
                return $this->validarEntero($campo);
        }
    }

    /**
     * Valida si el campo es una cadena de caracteres
     *
     * @param Campo $campo Campo a validar.
     *
     * @return bool Devuelve **true** si el campo es una cadena de caracteres o **false** de lo contrario.
     */
    public function validarCadena(Campo $campo): bool
    {
        if( is_string($campo->valor()) === false ) {
            $this->error($campo, self::NO_ES_STRING, Errores::ERROR_NO_ES_STRING);
            return false;
        }

        return true;
    }

    /**
     * Valida si el campo es un entero
     *
     * Verifica que el campo corresponda con un entero. Si el campo es una cadena se valida
     * que su contenido sea un número entero válido.
     *
     * @param Campo $campo Campo a validar.
     *
     * @return bool Devuelve **true** si el campo es un número entero válido o **false** de lo contrario.
     */
    public function validarEntero(Campo $campo): bool
    {
        if( is_int($campo->valor()) ) {
            return true;
        }

        if( is_string($campo->valor()) && preg_match('/^-?[0-9]+$/', $campo->valor()) === 1 ) {
            return true;
        }

        $this->error($campo, self::NO_ES_INT, Errores::ERROR_NO_ES_INT);
        return false;
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

    /**
     * Escribe el mensaje y el código de error en el campo
     *
     * @param Campo  $campo   Campo donde se escribirá.
     * @param string $mensaje Mensaje de error.
     * @param int    $codigo  Código de error.
     */
    private function error(Campo $campo, string $mensaje, int $codigo)
    {
        $campo->error()->codigo($codigo);
        $campo->error()->mensaje($mensaje);
    }

}
