<?php

namespace Gof\Sistema\Formulario\Datos\Campo\Validar;

use Gof\Sistema\Formulario\Validar\ValidarLimiteFloat;

/**
 * Agrega validación de límite a un campo
 *
 * Trait que agrega una función para validar los límites a un campo.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo\Validar
 */
trait LimiteFloat
{

    /**
     * Valida los límites del campo
     *
     * @return ValidarLimiteFloat
     */
    public function limite(): ValidarLimiteFloat
    {
        return $this->validador(ValidarLimiteFloat::class);
    }

}
