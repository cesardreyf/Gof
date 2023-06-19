<?php

namespace Gof\Sistema\Formulario\Interfaz\Campo;

/**
 * Interfaz para los campos validables
 *
 * Interfaz que deben implementar todos los campos que sean validables.
 *
 * @package Gof\Sistema\Formulario\Interfaz\Campo
 */
interface Validable
{
    /**
     * Valida el valor del campo
     *
     * @return ?bool Devuelve el estado de la validación.
     */
    public function validar(): ?bool;
}
