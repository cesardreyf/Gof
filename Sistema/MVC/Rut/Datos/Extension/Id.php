<?php

namespace Gof\Sistema\MVC\Rut\Datos\Extension;

/**
 * Rasgo que agrega un identificador a cada ruta
 *
 * @package Gof\Sistema\MVC\Rut\Datos\Extension
 */
trait Id
{
    /**
     * Identificador numérico
     *
     * @var int
     */
    private int $id;

    /**
     * Obtiene el identificador numérico
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Asigna el identificador numérico
     *
     * @param int $idAsignado
     */
    public function asignarIdentificador(int $idAsignado)
    {
        $this->id = $idAsignado;
    }

}
