<?php

namespace Gof\Sistema\MVC\Rut\Observador;

use Gof\Gestor\Enrutador\Rut\Eventos\Al;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Evento;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Sistema\MVC\Inters\Contenedor\Gestor;
use Gof\Sistema\MVC\Rut\Datos\Ruta;

/**
 * Observador para los identificadores de las rutas del sistema MVC
 *
 * Cada vez que se genere un evento al agregar una nueva ruta se asignará a cada
 * ruta un identificador numérico único.
 *
 * @package Gof\Sistema\MVC\Rut\Observador
 */
class GestorDeInters implements Observador
{
    /**
     * Gestor de inters
     *
     * @var Gestor
     */
    private Gestor $gestorDeInters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gestorDeInters = new Gestor();
    }

    /**
     * Asigna un identificador a las rutas
     *
     * Al producirse el evento de tipo **Al::Agregar** se asigna un id único a
     * la ruta.
     *
     * @param Evento $evento
     */
    public function evento(Evento $evento)
    {
        if( $evento->tipo() === Al::Agregar ) {
            $this->asignarGestorDeInters($evento->ruta());
        }
    }

    /**
     * Asigna el gestor de inters a la ruta
     *
     * @param Ruta $ruta Instancia de la ruta
     */
    public function asignarGestorDeInters(Ruta $ruta)
    {
        $ruta->asignarGestorDeInters($this->gestorDeInters);
    }

}
