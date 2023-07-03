<?php

namespace Gof\Contrato\Session;

/**
 * Contrato para los gestores de session
 *
 * @package Gof\Contrato\Session
 */
interface Session
{
    /**
     * Inicializa la session
     *
     * @return bool
     */
    public function iniciar(): bool;

    /**
     * Finaliza la session
     *
     * @return bool
     */
    public function finalizar(): bool;

    /**
     * Devuelve el estado de la sesión
     *
     * @return int
     */
    public function estado(): int;

    /**
     * Destruye la session
     *
     * @return bool
     */
    public function destruir(): bool;

    /**
     * Obtiene o define el nombre de la session
     *
     * @param string $nombre
     *
     * @return string
     */
    public function nombre(?string $nombre): string;

    /**
     * Verifica si existe la clave dentro de session
     *
     * @param string $clave
     *
     * @return bool
     */
    public function existe(string $clave): bool;

    /**
     * Obtiene el valor asociado a la clave
     *
     * @param string $clave
     *
     * @return mixed
     */
    public function obtener(string $clave): mixed;

    /**
     * Obtiene una referencia al elemento asociado a la clave
     *
     * @param string $clave
     *
     * @return mixed
     */
    public function& obtenerReferencia(string $clave): mixed;

    /**
     * Define un valor asociado a una clave
     *
     * @param string $clave
     * @param mixed  $valor
     *
     * @return mixed
     */
    public function definir(string $clave, mixed $valor): mixed;

    /**
     * Elimina un elemento de session asociado a la clave
     *
     * @param string $clave
     */
    public function eliminar(string $clave);
}
