<?php

namespace Gof\Sistema\Formulario\Validar;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Campo\Validable;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Valida que todas las expresiones regulares coincidan con el valor del campo
 *
 * Clase encargada de validar que el valor de un campo de tipo string coincida
 * con un conjunto de expresiones regulares.
 *
 * @package Gof\Sistema\Formulario\Validar
 */
class ValidarExpresionRegular implements Validable
{
    /**
     * @var int
     */
    private const EXPRESION = 0;

    /**
     * @var int
     */
    private const MENSAJE = 1;

    /**
     * @var string Mensaje de error para cuando una de las expresiones no coincida con el valor
     */
    public const CADENA_INVALIDA = 'La cadena no coincide con la expresión regular';

    /**
     * @var Campo Instancia del campo a validar.
     */
    private Campo $campo;

    /**
     * @var array Lista de expresiones regulares y sus posibles mensajes de error.
     */
    private array $listaDeExpresiones;

    /**
     * Constructor
     *
     * @param Campo $campo Instancia del campo a validar.
     */
    public function __construct(Campo $campo)
    {
        $this->campo = $campo;
        $this->listaDeExpresiones = [];
    }

    /**
     * Valida que el conjunto de expresiones regulares coincida con el valor del campo
     *
     * Recorre la lista de expresiones regulares guardadas y los valida con el
     * valor del campo. Si todas las expresiones devuelven **true** la
     * validación será correcta, caso contrario la función devolverá **false**.
     *
     * Si existen errores previos o el valor del campo no es un string la
     * función devolverá un valor **null**.
     *
     * Si una de las validaciones falla las siguientes no se ejecutarán.  
     * Si no existen expresiones regulares que validar la función devuelve **true**.
     *
     * @return ?bool Devuelve el estado de la validación.
     */
    public function validar(): ?bool
    {
        if( $this->campo->error()->hay() || !is_string($this->campo->valor()) ) {
            return null;
        }

        $todasLasExpresionesRegularesSonValidas = true;
        foreach( $this->listaDeExpresiones as $regex ) {
            if( !preg_match($regex[self::EXPRESION], $this->campo->valor()) ) {
                Error::reportar(
                    $this->campo,
                    $regex[self::MENSAJE] ?? self::CADENA_INVALIDA,
                    Errores::ERROR_REGEX_CADENA_INVALIDA
                );

                $todasLasExpresionesRegularesSonValidas = false;
                break;
            }
        }

        return $todasLasExpresionesRegularesSonValidas;
    }

    /**
     * Agrega una expresión regular a la lista
     *
     * Agrega una expresión regular que será usada al momento de validar con el valor
     * del campo.
     *
     * Si se quiere se puede agregar un mensaje de error asociado a la expresión regular.
     * En caso de que falle la validación se escribirá el mensaje en el buffer de errores
     * del campo en lugar del mensaje genérico.
     *
     * La función empleada para validar la expresión es **preg_match()**.
     *
     * @param string  $expresion      Expresión regular.
     * @param ?string $mensajeDeError Mensaje de error que será usado si lo hubiera (opcional).
     */
    public function agregar(string $expresion, ?string $mensajeDeError = null)
    {
        $this->listaDeExpresiones[] = [
            self::EXPRESION => $expresion,
            self::MENSAJE => $mensajeDeError
        ];
    }

}
