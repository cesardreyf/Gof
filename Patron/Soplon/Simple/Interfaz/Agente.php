<?php

namespace Gof\Patron\Soplon\Simple\Interfaz;

/**
 * Interfaz a implementar por los agentes
 *
 * Interfaz que todos los agentes del patrón soplón simple deben implementar.
 *
 * @package Gof\Patron\Soplon\Simple\Interfaz
 */
interface Agente
{
    /**
     * Avisa al agente
     */
    public function avisar();
}
