<?php

namespace Gof\Sistema\Formulario\Datos\Campo\Validar;

use Gof\Sistema\Formulario\Validar\ValidarLimite;

/**
 * Agrega validación de límite a un campo
 *
 * Trait que agrega una función para validar los límites a un campo.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo\Validar
 */
trait Limite
{

    /**
     * Valida los límites del campo
     *
     * @return ValidarLimite
     */
    public function limite(): ValidarLimite
    {
        return $this->validador(ValidarLimite::class);
    }

}
