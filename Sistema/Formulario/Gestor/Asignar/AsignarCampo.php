<?php

namespace Gof\Sistema\Formulario\Gestor\Asignar;

use Gof\Sistema\Formulario\Datos\Campo as CampoBasico;
use Gof\Sistema\Formulario\Datos\Campo\TipoArray;
use Gof\Sistema\Formulario\Datos\Campo\TipoBool;
use Gof\Sistema\Formulario\Datos\Campo\TipoFloat;
use Gof\Sistema\Formulario\Datos\Campo\TipoInt;
use Gof\Sistema\Formulario\Datos\Campo\TipoSelect;
use Gof\Sistema\Formulario\Datos\Campo\TipoString;
use Gof\Sistema\Formulario\Datos\Campo\TipoTabla;
use Gof\Sistema\Formulario\Interfaz\Campo;
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
            case Tipos::TIPO_INT:
                return new TipoInt($alias);

            case Tipos::TIPO_STRING: 
                return new TipoString($alias);

            case Tipos::TIPO_ARRAY:
                return new TipoArray($alias);

            case Tipos::TIPO_TABLA:
                return new TipoTabla($alias);

            case Tipos::TIPO_FLOAT:
                return new TipoFloat($alias);

            case Tipos::TIPO_SELECT:
                return new TipoSelect($alias);

            case Tipos::TIPO_BOOL:
                return new TipoBool($alias);

            default:
                return new CampoBasico($alias, $tipo);
        }
    }

}
