<?php

namespace Gof\Gestor\Enrutador\Rut\Interfaz;

/**
 * Interfaz para los nodos del enrutador por nodos
 *
 * @package Gof\Gestor\Enrutador\Rut\Interfaz
 */
interface Ruta
{
    /**
     * Obtiene el nombre completo de la clase
     *
     * @return string Devuelve el nombre completo de la clase.
     */
    public function clase(): string;

    /**
     * Obtiene el nombre de la ruta
     *
     * @return string
     */
    public function ruta(): string;

    /**
     * Obtiene una lista de nodos hijos
     *
     * @return array<int, Ruta> Devuelve un conjunto de nodos.
     */
    public function hijos(): ?array;
}
