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
     * @var Datos Lista de gestores de impresión
     */
    private Datos $impresion;

    /**
     * Constructor
     *
     * @param Datos Lista de gestores encargados de guardar el error
     */
    public function __construct(Datos $gestores, Datos $impresores)
    {
        $this->guardado = $gestores;
        $this->impresion = $impresores;
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

    /**
     * Obtiene la lista de gestores de impresión
     *
     * @return Datos
     */
    public function impresion(): Datos
    {
        return $this->impresion;
    }

}
