<?php

namespace Gof\Sistema\Propiedades\Simple;

use Gof\Sistema\Propiedades\Simple\Modulos\Actualizacion;
use Gof\Sistema\Propiedades\Simple\Modulos\Borrado;
use Gof\Sistema\Propiedades\Simple\Modulos\Obtencion;
use Gof\Sistema\Propiedades\Simple\Modulos\Persistencia;

/**
 * Sistema de gestion de propiedades
 *
 * Sistema que gestiona las operaciones básicas de datos (Persistencia,
 * Actualizacion, Borrado y Obtención).
 *
 * Cada operación está dividida en un módulo diferente donde se almacenan las
 * propiedades y una lista de errores.
 *
 * @package Gof\Sistema\Propiedades\Simple
 */
class Propiedades
{
    /**
     * @var Persistencia Módulo de persistencia.
     */
    private Persistencia $persistencia;

    /**
     * @var Actualizacion Módulo de acutalización.
     */
    private Actualizacion $actualizacion;

    /**
     * @var Borrado Módulo de borrado.
     */
    private Borrado $borrado;

    /**
     * @var Obtencion Módulo de obtención.
     */
    private Obtencion $obtencion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->persistencia  = new Persistencia();
        $this->actualizacion = new Actualizacion();
        $this->borrado       = new Borrado();
        $this->obtencion     = new Obtencion();
    }

    /**
     * Módulo de persistencia
     *
     * @return Persistencia Devuelve el módulo de persistencia.
     */
    public function persistencia(): Persistencia
    {
        return $this->persistencia;
    }

    /**
     * Módulo de actualización
     *
     * @return Actualizacion Devuelve el módulo de actualización.
     */
    public function actualizacion(): Actualizacion
    {
        return $this->actualizacion;
    }

    /**
     * Módulo de borrado
     *
     * @return Borrado Devuelve el módulo de borrado.
     */
    public function borrado(): Borrado
    {
        return $this->borrado;
    }

    /**
     * Módulo de obtención
     *
     * @return Obtencion Devuelve el módulo de obtencion.
     */
    public function obtencion(): Obtencion
    {
        return $this->obtencion;
    }

}
