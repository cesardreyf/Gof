<?php

namespace Gof\Contrato\Registro;

/**
 * Interfaz para gestores de registros
 *
 * @package Gof\Contrato\Registro
 */
interface Registro
{
    /**
     * Registra el mensaje
     *
     * @param string $mensaje Mensaje a ser registrado
     */
    public function registrar(string $mensaje): bool;

    /**
     * Persiste los mensajes
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function volcar(): bool;
}
