<?php

namespace Gof\Sistema\MVC\Rut\Datos\Extension;

use Gof\Sistema\MVC\Inters\Contenedor\Gestor;
use Gof\Sistema\MVC\Inters\Contenedor\Contenedor;

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
    /**
     * Método abstracto a implementar por la clase que use este trait
     *
     * NOTA: se asume que la clase implementa la interfaz Id.
     *
     * @see Gof\Interfaz\Id
     */
    abstract public function id(): int;

    /**
     * Instancia del gestor de inters
     *
     * @var Gestor
     */
    private Gestor $gInters;

    /**
     * Contenedor dedicado solo para esta ruta
     *
     * @var Contenedor
     */
    private Contenedor $contenedor;

    /**
     * Obtiene el gestor de inters para la ruta
     *
     * @return Contenedor
     */
    public function inters(): Contenedor
    {
        return $this->contenedor ?? $this->contenedor = $this->gInters->segunId($this);
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
