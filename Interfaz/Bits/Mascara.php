<?php

namespace Gof\Interfaz\Bits;

/**
 * Interfaz para una clase que representa una máscara de bits
 *
 * @package Gof\Interfaz\Bits
 */
interface Mascara
{
    /**
     * Obtiene el valor de la máscara de bits
     *
     * @return int
     */
    public function obtener(): int;

    /**
     * Define el valor de la máscara de bits
     *
     * @param int $valor Nuevo valor a definir
     *
     * @return int Valor actual de la máscara de bits
     */
    public function definir(int $valor): int;

    /**
     * Activa los bits
     *
     * @param int $bits Bits a ser activados
     *
     * @return int Valor actual de la máscara de bits
     */
    public function activar(int $bits): int;

    /**
     * Desactiva los bits
     *
     * @param int $bits Bits a ser desactivados
     *
     * @return int Valor actual de la máscara de bits
     */
    public function desactivar(int $bits): int;

    /**
     * Valida si los bits están activos
     *
     * @param int $bits Bits a ser validados
     *
     * @return bool Devuelve **true** si todos los bits indicados están activos, **false** de lo contrario.
     */
    public function activados(int $bits): bool;

    /**
     * Valida si los bits están desactivados
     *
     * @param int $bits Bits a ser desactivados
     *
     * @return bool Devuelve **true** si todos los bits indicados están desactivados, **false** de lo contrario.
     */
    public function desactivados(int $bits): bool;
}
