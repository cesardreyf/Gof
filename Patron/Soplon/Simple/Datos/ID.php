<?php

namespace Gof\Patron\Soplon\Simple\Datos;

/**
 * Almacena un identificador asociado a un agente en una lista
 *
 * @package Gof\Patron\Soplon\Simple\Datos
 */
class ID
{
    /**
     * Almacena un identificador numérico
     *
     * @var int
     */
    private int $id;

    /**
     * Constructor
     *
     * @param int $id Identificador numérico.
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Obtiene el identificador numérico
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

}
