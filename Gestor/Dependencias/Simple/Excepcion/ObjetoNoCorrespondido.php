<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando se intenta asignar un tipo de datos diferente al nombre de la clase o interfaz
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class ObjetoNoCorrespondido extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $clase Nombre de la clase o interfaz
     * @param mixed  $objeto Objeto al que se intentó asignar
     */
    public function __construct(string $clase, $objeto)
    {
        parent::__construct("No se puede asignar a la clase '{$clase}' el tipo: " . gettype($objeto));
    }

}
