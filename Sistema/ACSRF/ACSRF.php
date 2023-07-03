<?php

namespace Gof\Sistema\ACSRF;

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
     * Clave que usará el token
     *
     * @var string
     */
    public string $clave = 'acsrf_token';

    /**
     * Constructor
     *
     * @param Session $sesion Gestor de sesión
     */
    public function __construct(Session $sesion)
    {
        $this->sesion = $sesion;
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
        $hash = md5(uniqid(mt_rand(), true));
        return $this->sesion->definir($this->clave, $hash);
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

    /*
     * Envía el hash por cookie
     */
    // public function enviarCookie()
    // {
    //     $this->cookie->definir($this->claveCookie, $this->generar());
    // }

}
