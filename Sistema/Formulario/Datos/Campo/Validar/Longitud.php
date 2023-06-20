<?php

namespace Gof\Sistema\Formulario\Datos\Campo\Validar;

use Gof\Sistema\Formulario\Validar\ValidarLongitud;

/**
 * Agrega validación de longitud de cadenas
 *
 * Trait que agrega una función para validar la longitud de un string.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo\Validar
 */
trait Longitud
{

    /**
     * Valida la longitud
     *
     * @return ValidarLongitud
     */
    public function longitud(): ValidarLongitud
    {
        return $this->validador(ValidarLongitud::class);
    }

}
