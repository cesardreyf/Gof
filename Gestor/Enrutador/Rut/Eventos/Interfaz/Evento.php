<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos\Interfaz;

use Gof\Gestor\Enrutador\Rut\Eventos\Al;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta;

/**
 * Interfaz para los eventos
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos\Interfaz
 */
interface Evento
{
    /**
     * Devuelve el tipo de evento
     *
     * @return Al
     */
    public function tipo(): Al;

    /**
     * Devuelve la ruta que produjo el evento
     *
     * @return Ruta
     */
    public function ruta(): Ruta;
}
