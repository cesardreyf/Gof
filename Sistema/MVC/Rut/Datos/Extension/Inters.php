<?php

namespace Gof\Sistema\MVC\Rut\Datos\Extension;

use Gof\Sistema\MVC\Rut\Inters\Gestor;
use Gof\Sistema\MVC\Rut\Inters\Subgestor;

/**
 * Proporciona características de gestionar inters para las rutas
 *
 * Ofrece el método inters para cada ruta, el cual permite gestionar los inters
 * de cada ruta.
 *
 * @package Gof\Sistema\MVC\Rut\Datos\Extension
 */
trait Inters
{
    abstract public function id(): int;

    /**
     * Instancia del gestor de inters
     *
     * @var Gestor
     */
    private Gestor $gInters;

    /**
     * Subgestor dedicado solo para esta ruta
     *
     * @var Subgestor
     */
    private Subgestor $miGestorDeInters;

    /**
     * Obtiene el gestor de inters para la ruta
     *
     * @return Gestor
     */
    public function inters(): Subgestor
    {
        return $this->miGestorDeInters ?? $this->miGestorDeInters = $this->gInters->segunId($this->id());
    }

    /**
     * Asigna el gestor de inters a la ruta
     *
     * @param Gestor $gestor Instancia del gestor.
     */
    public function asignarGestorDeInters(Gestor $gestor)
    {
        $this->gInters = $gestor;
    }

}
