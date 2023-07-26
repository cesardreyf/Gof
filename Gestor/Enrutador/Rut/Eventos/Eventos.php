<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;

/**
 * Almacena y gestiona los eventos del EnrutadorConEventos
 *
 * @package Gof\Gestor\Enrutador\Rut\Buchon
 */
class Eventos
{
    /**
     * Lista de observadores
     *
     * @var Observador[]
     */
    private array $observadores = [];

    /**
     * Avisa de un evento a los observadores
     *
     * @param IRuta $ruta   Ruta que provoca el evento
     * @param Al    $evento Evento producido
     */
    public function avisar(IRuta $ruta, Al $evento)
    {
        foreach( $this->observadores as $observador ) {
            $metodo = "al{$evento->name}";
            $observador->{$metodo}($ruta);
        };
    }

    /**
     * Agrega un nuevo observador
     *
     * Agrega un observador que recibirá las notificaciones de los eventos
     * producidos.
     *
     * @param Observador $observador Instancia del observador.
     */
    public function agregar(Observador $observador)
    {
        $this->observadores[] = $observador;
    }

}
