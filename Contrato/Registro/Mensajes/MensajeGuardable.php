<?php

namespace Gof\Contrato\Registro\Mensajes;

/**
 * Interfaz para guardar los registros
 *
 * @package Gof\Contrato\Registro\Mensajes
 */
interface MensajeGuardable
{
    /**
     * Guarda los mensajes
     *
     * @return bool Devuelve el estado de la operación
     */
    public function guardar(): bool;
}
