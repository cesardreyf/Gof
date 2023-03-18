<?php

namespace Gof\Sistema\Reportes\Interfaz;

/**
 * Interfaz empleada por el sistema de reportes para los gestores de reportes
 *
 * @package Gof\Sistema\Reportes\Interfaz
 */
interface Reportero
{
    /**
     * Reporta algo
     *
     * @param mixed $datos Datos a ser reportados.
     *
     * @return bool Devuelve **true** si fue exitoso o **false** de lo contrario.
     */
    public function reportar(mixed $datos): bool;

    /**
     * Plantilla para la traducción de los datos
     *
     * @return Plantilla
     */
    public function plantilla(): Plantilla;

    /**
     * Indica si se imprimirán los mensajes traducidos o no
     *
     * @return bool Devuelve **true** si está configurado para imprimir los reportes.
     */
    public function imprimir(): bool;
}
