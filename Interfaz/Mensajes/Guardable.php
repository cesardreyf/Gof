<?php

namespace Gof\Interfaz\Mensajes;

/**
 * Interfaz para gestores de guardado de mensajes
 *
 * @package Gof\Interfaz\Mensajes
 */
interface Guardable
{
    /**
     * Guarda el mensaje
     *
     * @param string $mensaje Mensaje a ser guardado
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function guardar(string $mensaje): bool;
}
