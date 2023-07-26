<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos\Interfaz;

/**
 * Interfaz que deben implementar los observadores
 *
 * Todos los observadores que quieran recibir los eventos producidos por el
 * enrutador deben implementar esta interfaz donde recibirán el evento
 * producido.
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos\Interfaz
 */
interface Observador
{
    /**
     * Recibe el evento producido
     *
     * @param Evento $evento Instancia del evento producido.
     */
    public function evento(Evento $evento);
}
