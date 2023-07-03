<?php

namespace Gof\Sistema\ACSRF;

use Gof\Contrato\Cookies\Cookies;
use Gof\Contrato\Session\Session;

/**
 * Sistema Anti CSRF
 *
 * @package Gof\Sistema\ACSRF
 */
class ACSRF
{
    /**
     * Instancia del gestor de sesión
     *
     * @var Session
     */
    private Session $sesion;

    /**
     * Instancia del gestor de cookies
     *
     * @var Cookies
     */
    private Cookies $cookies;

    /**
     * Clave que usará el token
     *
     * @var string
     */
    public string $clave = 'acsrf_token';

    /**
     * Clave que se usará para obtener o definir el hash en el cliente
     *
     * El hash se enviará o obtendrá mediante las cookies empleando esta clave
     * asociada.
     *
     * @var string
     */
    public string $claveCookie = 'acsrf_token';

    /**
     * Almacena temporalmente el último hash generado
     *
     * @var ?string
     */
    private ?string $hashTemporal;

    /**
     * Duración que tendrá la cookie enviada
     *
     * Cuando se envíe un hash por cookies la misma durará lo que se indique
     * aquí.  Si esta variable es igual a 0 la duración será la que tenga por
     * defecto el gestor de cookies.
     *
     * @var int
     */
    public int $duracion = 0;

    /**
     * Constructor
     *
     * @param Session $sesion Gestor de sesión
     * @param Cookies $cookies Gestor de cookies
     */
    public function __construct(Session $sesion, Cookies $cookies)
    {
        $this->sesion = $sesion;
        $this->cookies = $cookies;
    }

    /**
     * Genera el hash y lo coloca en la sesión
     *
     * Crea el hash del token y le pide al gestor de sesión que lo coloque en
     * la clave reservada.
     *
     * @return string Devuelve el hash generado.
     */
    public function generar(): string
    {
        $hashTemporal = md5(uniqid(mt_rand(), true));
        return $this->sesion->definir($this->clave, $hashTemporal);
    }

    /**
     * Valida que el hash recibido sea el mismo que se generó
     *
     * @param string $hash Hash recibido.
     *
     * @return bool Devuelve **true** si es válido o **false** de lo contrario.
     */
    public function validar(string $hash): bool
    {
        $hashInterno = $this->sesion->obtener($this->clave);

        if( is_null($hashInterno) || !is_string($hashInterno) ) {
            return false;
        }

        return $hashInterno === $hash;
    }

    /**
     * Valida el hash obteniéndolo desde una cookie
     *
     * Obtiene el hash automáticamente desde una cookie.
     *
     * @return bool Devuelve **true** si el hash es válido o **false** de lo contrario.
     */
    public function validarDesdeCookies(): bool
    {
        $hash = $this->cookies->obtener($this->claveCookie);

        if( is_null($hash) ) {
            return false;
        }

        return $this->validar($hash);
    }

    /**
     * Envía el hash por cookies
     *
     * Envía una cookie al cliente con el hash generado.
     */
    public function enviarPorCookies()
    {
        $expire = 0;
        if( $this->duracion !== 0 ) {
            $expire = $this->cookies->duracion();
            $this->cookies->duracion($this->duracion, true);
        }

        $hash = $this->hashTemporal ?? $this->generar();
        $this->cookies->definir($this->claveCookie, $hash);

        if( $this->duracion !== 0 ) {
            $this->cookies->duracion($expire, false);
        }
    }

}
