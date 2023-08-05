<?php

namespace Gof\Patron\Soplon\Excepcion;

use Gof\Patron\Soplon\Base\ID;

/**
 * Excepción lanzada cuando no existe un agente solicitado
 *
 * @package Gof\Patron\Soplon\Excepcion
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
