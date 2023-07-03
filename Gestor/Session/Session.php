<?php

namespace Gof\Gestor\Session;

use Gof\Contrato\Session\Session as ISession;
use Gof\Datos\Bits\Mascara\MascaraDeBits;

/**
 * Gestor de session
 *
 * @package Gof\Gestor\Session
 */
class Session implements ISession
{
    /**
     * @var int
     */
    public const SESION_LIBRE = PHP_SESSION_NONE;

    /**
     * @var int
     */
    public const SESION_OCUPADA = PHP_SESSION_ACTIVE;

    /**
     * @var int
     */
    public const SESION_DESHABILITADA = PHP_SESSION_DISABLED;

    /**
     * @var int
     */
    public const ELIMINAR_COOKIES_AL_DESTRUIR = 1;

    /**
     * @var array
     */
    private array $opciones;

    /**
     * @var MascaraDeBits
     */
    private MascaraDeBits $config;

    /**
     * Constructor
     *
     * @param array $opciones
     * @param int   $config
     */
    public function __construct(array $opciones = [], int $config = 0)
    {
        $this->opciones($opciones);
        $this->config = new MascaraDeBits($config);
    }

    /**
     * Inicializa la session
     *
     * @return bool
     */
    public function iniciar(): bool
    {
        // Si la sesión no están habilitadas
        // o si ya existe una sesión activa
        if( $this->estado() !== self::SESION_LIBRE ) {
            return false;
        }

        return session_start($this->opciones);
    }

    /**
     * Finaliza la session
     *
     * @return bool
     */
    public function finalizar(): bool
    {
        // Si la sesión no están habilitadas
        // o si aún no se inició una sesión
        if( $this->estado() !== self::SESION_OCUPADA ) {
            return false;
        }

        // Finaliza la sesión actual y almacena la información
        session_write_close();

        // Limpia la variable superglobal
        $_SESSION = array();
        return true;
    }

    /**
     * Estado de la sesión
     *
     * @return int
     */
    public function estado(): int
    {
        return session_status();
    }

    /**
     * Destruye la sesión
     *
     * @return bool
     */
    public function destruir(): bool
    {
        if( $this->estado() !== self::SESION_OCUPADA ) {
            return false;
        }

        if( $this->config->activados(self::ELIMINAR_COOKIES_AL_DESTRUIR) && ini_get('session.use_cookies') ) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', -1, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        $_SESSION = array();
        return session_destroy();
    }

    /**
     * Verifica que exista un elemento con dicha clave
     *
     * @param string $clave
     *
     * @return bool
     */
    public function existe(string $clave): bool
    {
        return isset($_SESSION[$clave]);
    }

    /**
     * Obtiene el valor asociado a la clave
     *
     * @param string $clave
     *
     * @return mixed
     */
    public function obtener(string $clave): mixed
    {
        return $_SESSION[$clave] ?? null;
    }

    /**
     * Obtiene una referencia a un elemento de la sesión
     *
     * @param string $clave
     *
     * @return mixed
     */
    public function& obtenerReferencia(string $clave): mixed
    {
        return $_SESSION[$clave];
    }

    /** 
     * Define el valor de un elemento en sesión
     *
     * @param string $clave
     * @param mixed  $valor
     *
     * @return mixed
     */
    public function definir(string $clave, mixed $valor): mixed
    {
        return $_SESSION[$clave] = $valor;
    }

    /**
     * Elimina un elemento de la sesión
     *
     * @param string $clave
     */
    public function eliminar(string $clave)
    {
        $_SESSION[$clave] = null;
        unset($_SESSION[$clave]);
    }

    /**
     * Define las opciones que se pasarán al iniciar sesión
     *
     * Establece el array que se pasará por parámetros a la función session_start()
     *
     * @param array $opciones
     *
     * @return array
     */
    public function opciones(array $opciones): array
    {
        return $this->opciones = $opciones;
    }

    /**
     * Establece el valor de un elemento asociado a la clave dentro de las opciones
     *
     * @param string $directiva
     * @param mixed  $valor
     *
     * @return mixed
     */
    public function opcion(string $directiva, mixed $valor): mixed
    {
        return $this->opciones[$directiva] = $valor;
    }

    /**
     * Obtiene la máscara de bits que controla la configuración del gestor
     *
     * @return MascaraDeBits
     */
    public function configuracion(): MascaraDeBits
    {
        return $this->config;
    }

    /**
     * Obtiene o define el nombre de la sesión
     *
     * @param ?string $nombre Nombre de la sesión o **null** para obtener el actual.
     *
     * @return string Devuelve el nombre actual de la sesión.
     */
    public function nombre(?string $nombre = 'PHPSESSID'): string
    {
        if( $this->estado() !== self::SESION_LIBRE ) return '';
        return session_name($nombre);
    }

}
