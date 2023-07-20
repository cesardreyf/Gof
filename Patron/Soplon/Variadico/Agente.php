<?php

namespace Gof\Patron\Soplon\Variadico;

/**
 * Interfaz para los agentes del patrón soplón variádico
 *
 * @package Gof\Patron\Soplon\Variadico
 */
interface Agente
{
    /**
     * Recibe el aviso del soplón
     *
     * @param mixed ...$informe Datos del soplón.
     */
    public function aviso(mixed ...$informe);
}
