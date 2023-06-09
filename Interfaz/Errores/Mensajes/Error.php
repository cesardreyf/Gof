<?php

namespace Gof\Interfaz\Errores\Mensajes;

use Gof\Interfaz\Errores\Errores;

/**
 * Interfaz para mensajes de errores
 *
 * @package Gof\Interfaz\Errores\Mensajes
 */
interface Error extends Errores
{
    /**
     * Obtiene o define el código de error
     *
     * @param ?int $error Código de error o **null** para obtener el actual.
     *
     * @return int Devuelve el código de error actual.
     */
    public function codigo(?int $error = null): int;

    /**
     * Obtiene o define el mensaje de error
     *
     * @param ?string $error Mensaje de error o **null** para obtener el actual.
     *
     * @return string Devuelve el mensaje de error actual.
     */
    public function mensaje(?string $error = null): string;
}
