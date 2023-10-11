<?php

namespace Gof\Gestor\Cookies;

use Gof\Contrato\Cookies\Cookies as ICookies;

/**
 * Gestor de cookies (básico)
 *
 * @package Gof\Gestor\Cookies
 */
class Cookies implements ICookies
{
    /**
     * @var array
     */
    private array $opciones;

    /**
     * Constructor
     *
     * @param int    $duracion
     * @param string $ruta
     * @param string $dominio
     * @param bool   $seguridad
     * @param bool   $solohttp
     */
    public function __construct(int $duracion = 0, string $ruta = '/', string $dominio = '', bool $seguridad = false, bool $solohttp = false)
    {
        $this->opciones = [
            'path'     => $ruta,
            'domain'   => $dominio,
            'expires'  => $duracion,
            'secure'   => $seguridad,
            'httponly' => $solohttp
        ];
    }

    /**
     * Obtiene el valor de la cookie asociada a la clave
     *
     * @param string $clave Clave asociada
     *
     * @return ?string Devuelve el valor o **null** si no existe.
     */
    public function obtener(string $clave): ?string
    {
        return $_COOKIE[$clave] ?? null;
    }

    /**
     * Define el valor de la cookie asociada a la clave
     *
     * @param string $clave Clave asociada.
     * @param string $valor Valor de la cookie.
     *
     * @return bool Devuelve **true** si no hubo errores al definir.
     */
    public function definir(string $clave, string $valor): bool
    {
        $_COOKIE[$clave] = $valor;
        return setcookie($clave, $valor, $this->opciones);
    }

    /**
     * Elimina uno o varios cookies
     *
     * @param string $clave     Clave asociada a la cookie a eliminar.
     * @param string ...$claves Más claves a eliminar (Opcional).
     */
    public function eliminar(string $clave, string ...$claves)
    {
        $tmp = $this->duracion();       //< Almacena la duración en tiempo unix
        $this->duracion(1, true);       //< Define la duración en 1 segundo (tunix)

        array_unshift($claves, $clave);
        foreach( $claves as $clave ) {
            $this->definir($clave, ''); //< Elimina la cookie
        }

        $this->duracion($tmp, true);    //< Reestablece la duración
    }

    /**
     * Obtiene o define la duración de la cookie
     *
     * @param ?int $duración Duración de la cooki o **null** para obtener el actual
     * @param bool $tunix    Indica si sumar al tiempo unix actual.
     *
     * @return int
     */
    public function duracion(?int $duracion = null, bool $tunix = false): int
    {
        if( is_null($duracion) ) {
            return $this->opciones['expires'];
        }

        if( $tunix === true ) {
            $duracion = time() + $duracion;
        }

        return $this->opciones['expires'] = (int)$duracion;
    }

    /**
     * Obtiene o define la ruta (path)
     *
     * @param ?string $ruta
     *
     * @return string
     */
    public function ruta(?string $ruta = null): string
    {
        return is_null($ruta) ? $this->opciones['path'] : $this->opciones['path'] = $ruta;
    }

    /**
     * Obtiene o define el dominio (domain)
     *
     * @param ?string $dominio
     *
     * @return string
     */
    public function dominio(?string $dominio = null): string
    {
        return is_null($dominio) ? $this->opciones['domain'] : $this->opciones['domain'] = $dominio;
    }

    /**
     * Obtiene o define la seguridad (secure)
     *
     * @param ?bool $seguridad
     *
     * @return bool
     */
    public function seguridad(?bool $seguridad = null): bool
    {
        return is_null($seguridad) ? $this->opciones['secure'] : $this->opciones['secure'] = $seguridad;
    }

    /**
     * Obtiene o define httponly
     *
     * @param ?bool $solohttp
     *
     * @return bool
     */
    public function solohttp(?bool $solohttp = null): bool
    {
        return is_null($solohttp) ? $this->opciones['httponly'] : $this->opciones['httponly'] = $solohttp;
    }

}
