<?php

namespace Gof\Sistema\MVC\Registros\Modulo;

use Gof\Interfaz\Lista\Datos;

/**
 * Módulo de operaciones
 * 
 * @package Gof\Sistema\MVC\Registros\Modulo
 */
class Operacion
{
    /**
     * @var Datos Lista de gestores de guardado
     */
    private Datos $guardado;

    /**
     * Constructor
     *
     * @param Datos Lista de gestores encargados de guardar el error
     */
    public function __construct(Datos $gestores)
    {
        $this->guardado = $gestores;
    }

    /**
     * Obtiene la lista de gestores de guardado
     *
     * @return Datos
     */
    public function guardado(): Datos
    {
        return $this->guardado;
    }

}
