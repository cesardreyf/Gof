<?php

namespace Gof\Gestor\Enrutador\Rut\Interfaz;

/**
 * Interfaz para los nodos del enrutador por nodos
 *
 * @package Gof\Gestor\Enrutador\Rut\Interfaz
 */
interface Nodo
{
    /**
     * Obtiene el nombre completo de la clase
     *
     * @return string Devuelve el nombre completo de la clase.
     */
    public function clase(): string;

    /**
     * Obtiene una lista de páginas asociadas a la clase
     *
     * @return string[] Devuelve una lista de páginas asociadas a la clase.
     */
    public function paginas(): array;

    /**
     * Obtiene una lista de nodos hijos
     *
     * @return array<int, Nodo> Devuelve un conjunto de nodos.
     */
    public function hijos(): array;
}
