<?php

namespace Gof\Sistema\Formulario\Gestor\Campos;

use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;

/**
 * Módulo encargado de validar un campo
 *
 * Clase que contiene la lógica de validación de los campos.
 *
 * @package Gof\Sistema\Formulario\Gestor\Campos
 */
class ValidarCampo
{
    /**
     * @var bool Indica que se debe limpiar los errores de los campos opcionales
     */
    private bool $limpiarErroresDeCampoOpcional = false;

    /**
     * Establece si limpiar o no los errores de los campos opcionales
     *
     * Si se establece a **true** los errores de los campos opcionales serán eliminados
     * luego de la validación.
     *
     * @param bool $limpiar
     */
    public function limpiarErroresDeCampoOpcional(bool $limpiar)
    {
        $this->limpiarErroresDeCampoOpcional = $limpiar;
    }

    /**
     * Valida el campo
     *
     * @param Campo $campo Instancia del campo a validar
     */
    public function validar(Campo $campo): bool
    {
        if( $campo->validar() ) {
            return true;
        }

        if( $campo->obligatorio() ) {
            return false;
        }

        $error = $campo->error()->codigo();
        $hayErroresIgnorables = $error === Errores::ERROR_CAMPO_INEXISTENTE
                             || $error === Errores::ERROR_CAMPO_VACIO;

        if( !$hayErroresIgnorables ) {
            return false;
        }

        if( $this->limpiarErroresDeCampoOpcional ) {
            $campo->error()->limpiar();
        }

        return true;
    }

}
