<?php

namespace Gof\Sistema\Formulario\Datos\Campo\Validar;

use Gof\Sistema\Formulario\Validar\ValidarExpresionRegular;

/**
 * Agrega validacion de expresiones regulares a cadenas
 *
 * Trait que agrega una función para validar expresiones regulares.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo\Validar
 */
trait Regex
{

    /**
     * Valida expresiones regulares
     *
     * @return ValidarExpresionRegular
     */
    public function regex(): ValidarExpresionRegular
    {
        return $this->validador(ValidarExpresionRegular::class);
    }

}
