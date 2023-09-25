<?php

namespace Gof\Contrato\Traductor;

/**
 * Contrato para acceder a un gestor de traducción por módulos
 *
 * @package Gof\Contrato\Traductor
 */
interface Traductor
{
    /**
     * Establece el módulo de traducción
     *
     * @param string $nombreDelModulo
     */
    public function modulo(string $nombreDelModulo);

    /**
     * Traduce el mensaje
     *
     * @param string $mensaje Mensaje a ser traducido según el módulo seleccionado
     *
     * @return string Mensaje traducido
     */
    public function traducir(string $mensaje): string;
}
