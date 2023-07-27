<?php

namespace Gof\Gestor\Enrutador\Rut\Excepcion;

/**
 * Excepción lanzada cuando el enrutador con eventos no puede hacer un seguimiento de las rutas
 *
 * El enrutador con eventos necesita saber cuándo se crean las rutas. Para ello
 * pasa el gestor de eventos a la ruta padre, el cual las pasa a las hijas, y
 * de ese modo se disparan los eventos de creación de rutas.
 *
 * @package Gof\Gestor\Enrutador\Rut\Excepcion
 */
class RutaSinSeguimiento extends Excepcion
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('No se puede hacer un seguimiento de los eventos de las rutas sin la instancia del gestor de eventos de la ruta padre');
    }

}
