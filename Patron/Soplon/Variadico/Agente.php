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
     * Avisa a los agentes y le pasa el informe
     *
     * @param mixed ...$informe Datos del soplón.
     */
    public function avisar(mixed ...$informe);
}
