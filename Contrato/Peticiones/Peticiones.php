<?php

namespace Gof\Contrato\Peticiones;

use Gof\Interfaz\Lista;

/**
 * Interfaz para acceder a un gestor de peticiones
 *
 * Interfaz a ser implementada por los gestores de peticiones para procesar una
 * solicitud y generar una lista ordenada de recursos en forma de array.
 *
 * @package Gof\Contrato\Peticiones
 */
interface Peticiones extends Lista
{
    /**
     * Procesa la solicitud
     *
     * @param string $peticion
     *
     * @return bool Devuelve el estado de la operación.
     */
    public function procesar(string $peticion): bool;
}
