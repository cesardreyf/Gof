<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observadores as IObservadores;

/**
 * Gestor de eventos
 *
 * Almacena y gestiona los eventos del EnrutadorConEventos
 *
 * @package Gof\Gestor\Enrutador\Rut\Buchon
 */
class Gestor
{
    /**
     * Gestor de observadores
     *
     * @var Observadores
     */
    private Observadores $observadores;

    /**
     * Constructor
     */
    public function __construct(?IObservadores $observadores = null)
    {
        $this->observadores = $observadores ?? new Observadores();
    }

    /**
     * Avisa de un evento a los observadores
     *
     * @param IRuta $ruta   Ruta que provoca el evento
     * @param Al    $evento Evento producido
     */
    public function generar(Evento $evento)
    {
        foreach( $this->observadores->lista() as $observador ) {
            $observador->evento($evento);
        };
    }

    /**
     * Obtiene el gestor de observadores
     *
     * @return Observadores
     */
    public function observadores(): Observadores
    {
        return $this->observadores;
    }

}
