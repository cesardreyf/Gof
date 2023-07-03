<?php

namespace Gof\Contrato\Cookies;

/**
 * Interfaz para los gestores de cookies
 *
 * @package Gof\Contratos\Cookies
 */
interface Cookies
{
    /**
     * @param string $clave
     *
     * @return ?string
     */
    public function obtener(string $clave): ?string;

    /**
     * @param string $clave
     * @param string $valor
     *
     * @return ?string
     */
    public function definir(string $clave, string $valor): bool;

    /**
     * @param string $clave
     */
    public function eliminar(string $clave);

    /**
     * @param int $duracion
     *
     * @return int
     */
    public function duracion(?int $duracion): int;

    /**
     * @param string $path
     *
     * @return string
     */
    public function ruta(?string $path): string;

    /**
     * @param string $domain
     *
     * @return string
     */
    public function dominio(?string $domain): string;

    /**
     * @param bool $estado
     *
     * @return bool
     */
    public function seguridad(?bool $estado): bool;

    /**
     * @param bool $estado
     *
     * @return bool
     */
    public function solohttp(?bool $estado): bool;
}
