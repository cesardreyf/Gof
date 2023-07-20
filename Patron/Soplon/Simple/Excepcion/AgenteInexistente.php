<?php

namespace Gof\Patron\Soplon\Simple\Excepcion;

use Gof\Patron\Soplon\Simple\Datos\ID;

/**
 * Excepción lanzada cuando no existe un agente solicitado
 *
 * @package Gof\Patron\Soplon\Simple\Excepcion
 */
class AgenteInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param ID $agente Identificador del agente inexistente.
     */
    public function __construct(ID $agente)
    {
        parent::__construct("El agente con id {$agente->id()} no existe");
    }

}
