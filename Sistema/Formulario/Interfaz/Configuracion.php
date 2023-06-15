<?php

namespace Gof\Sistema\Formulario\Interfaz;

/**
 * Interfaz para el sitema de configuración con flags para la configuración
 *
 * @package Gof\Sistema\Formulario\Interfaz
 */
interface Configuracion
{
    /**
     * Valida los valores de los campos al ser creados los campos
     *
     * @see \Gof\Sistema\Formulario\Formulario::campo()
     *
     * @var int
     */
    public const VALIDAR_AL_CREAR = 1;
}
