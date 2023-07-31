<?php

namespace Gof\Sistema\MVC\Rut\Observador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Gestor\Enrutador\Rut\Eventos\Al;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Evento;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Sistema\MVC\Inters\Contenedor\Gestor;
use Gof\Sistema\MVC\Inters\Inters;
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
     * Instancia del módulo Inters
     *
     * @var Inters
     */
    private Inters $mInters;

    /**
     * Instancia del gestor de autoload
     *
     * @var Autoload
     */
    private Autoload $gAutoload;

    /**
     * Constructor
     *
     * @param Inters $inters Instancia del módulo Inters del sistema MVC.
     * @param Autoload $autoload Instancia del gestor de autoload.
     */
    public function __construct(Inters $inters, Autoload $autoload)
    {
        $this->gestorDeInters = new Gestor();
        $this->gAutoload = $autoload;
        $this->mInters = $inters;
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
        switch( $evento->tipo() ) {
            case Al::Agregar:
                $this->asignarGestorDeInters($evento->ruta());
                break;

            case Al::Procesar:
                $this->cargarEnElSistemaLosIntersDeLaRuta($evento->ruta());
                break;
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

    /**
     * Carga los inters contenidos en la ruta
     *
     * Carga los inters almacenados en la ruta a la aplicación del sistema MVC
     * para que los ejecute cuando sea necesario.
     *
     * @param Ruta $ruta Instancia de la ruta.
     */
    public function cargarEnElSistemaLosIntersDeLaRuta(Ruta $ruta)
    {
        $this->mInters->cargar($ruta->inters(), $this->gAutoload);
    }

}
