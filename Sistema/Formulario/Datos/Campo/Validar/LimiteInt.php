<?php

namespace Gof\Sistema\Formulario\Datos\Campo\Validar;

use Gof\Sistema\Formulario\Validar\ValidarLimiteInt;

/**
 * Agrega validación de límite a un campo
 *
 * Trait que agrega una función para validar los límites a un campo.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo\Validar
 */
trait LimiteInt
{

    /**
     * Valida los límites del campo
     *
     * @return ValidarLimiteInt
     */
    public function limite(): ValidarLimiteInt
    {
        return $this->validador(ValidarLimiteInt::class);
    }

}
