<?php

namespace Gof\Sistema\Formulario\Gestor\Asignar;

use Gof\Datos\Formulario\Campo as CampoBasico;
use Gof\Interfaz\Formulario\Campo;
use Gof\Sistema\Formulario\Interfaz\Tipos;

/**
 * Gestor de asignación de campos
 *
 * Clase encargada de gestionar qué tipo de Campo devolver según el tipo esperado.
 *
 * @package Gof\Sistema\Formulario\Gestor\Asignar
 */
abstract class AsignarCampo
{

    /**
     * Genera un Campo según el tipo de dato esperado
     *
     * @return Campo Devuelve un objeto de tipo Campo.
     */
    static public function segunTipo(string $alias, int $tipo): Campo
    {
        switch( $tipo ) {
            default:
                return new CampoBasico($alias, $tipo);
        }
    }

}
