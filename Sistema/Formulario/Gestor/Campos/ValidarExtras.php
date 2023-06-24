<?php

namespace Gof\Sistema\Formulario\Gestor\Campos;

use Gof\Sistema\Formulario\Interfaz\Campo;

/**
 * Módulo encargado de las validaciones extras de los campos
 *
 * @package Gof\Sistema\Formulario\Gestor\Campos
 */
abstract class ValidarExtras
{

    /**
     * Ejecuta las validaciones extras de los campos
     *
     * Recorre la lista de validadores extras del campo y ejecuta el método **validar**
     * del mismo en órden. Si uno falla se corta la validación y se devuelve **false**,
     * caso contrario devolverá **true**.
     *
     * @param Campo $campo Instancia del campo.
     *
     * @return bool Devuelve el estado de la validación.
     */
    static public function validar(Campo $campo): bool
    {
        foreach( $campo->vextra() as $validacionesExtra ) {
            if( !$validacionesExtra->validar() ) {
                return false;
            }
        }

        return true;
    }

}
