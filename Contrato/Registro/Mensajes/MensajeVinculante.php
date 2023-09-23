<?php

namespace Gof\Contrato\Registro\Mensajes;

/**
 * Interfaz para mensajes con la característica de poder vincular
 *
 * @package Gof\Contrato\Registro\Mensajes
 */
interface MensajeVinculante extends MensajeGuardable
{
    /**
     * Vincula un identificador a un mensaje
     *
     * @param int    $identificador Identificador numérico
     * @param string $mensaje       Mensaje por el cual será reemplazado
     */
    public function vincular(int $identificador, string $mensaje);

    /**
     * Obtiene el mensaje original
     *
     * @return string
     */
    public function obtenerMensajeOriginal(): string;
}
