<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos\Interfaz;

use Gof\Interfaz\Lista;

/**
 * Interfaz que deben implementar el gestor de observadores
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos\Interfaz
 */
interface Observadores extends Lista
{
    /**
     * Agrega un nuevo observador a la lista
     *
     * @param Observador Instancia del observador.
     */
    public function agregar(Observador $observador);
}
