<?php

namespace Gof\Gestor\Enrutador\Rut\Interfaz;

/**
 * Interfaz para rutas inexistentes
 *
 * @package Gof\Gestor\Enrutador\Rut\Interfaz
 */
interface Inexistente
{
    /**
     * Obtiene la clase del controlador encargado de procesar la ruta inexistente
     *
     * @return string Nombre completo de la clase.
     */
    public function clase(): string;
}
