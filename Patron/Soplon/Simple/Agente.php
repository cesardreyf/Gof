<?php

namespace Gof\Patron\Soplon\Simple;

/**
 * Interfaz a implementar por los agentes
 *
 * Interfaz que todos los agentes del patrón soplón simple deben implementar.
 *
 * @package Gof\Patron\Soplon\Simple
 */
interface Agente
{
    /**
     * Recibe un aviso del soplón
     */
    public function aviso();
}
