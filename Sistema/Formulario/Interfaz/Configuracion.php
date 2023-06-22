<?php

namespace Gof\Sistema\Formulario\Interfaz;

use Gof\Sistema\Formulario\Formulario;
use Gof\Sistema\Formulario\Formulario\Gestor\Campos as GestorDeCampos;

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
     * @see Formulario::campo()
     *
     * @var int
     */
    public const VALIDAR_AL_CREAR = 1;

    /**
     * Limpia los errores almacenados en los campos opcionales al ignorarlos
     *
     * @see GestorDeCampos::validar()
     *
     * @var int
     */
    public const LIMPIAR_ERRORES_CAMPOS_OPCIONALES = 2;

    /**
     * Limpia cualquier error almacenado en un campo al revalidar
     *
     * @see GestorDeCampos::validar()
     *
     * @var int
     */
    public const LIMPIAR_ERRORES_DE_CAMPOS_VALIDOS = 4;
}
