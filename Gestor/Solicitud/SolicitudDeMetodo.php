<?php

namespace Gof\Gestor\Solicitud;

use Gof\Gestor\Solicitud\Excepcion\ErrorDeTipo;

/**
 * Gestiona un tipo de solicitud
 *
 * @package Gof\Gestor\Solicitud
 */
class SolicitudDeMetodo
{

    /**
     * @var array Valores permitidos para **TRUE**
     */
    public const VALORES_BOOLEANOS_TRUE = ['on', 'true', 'yes', 'enabled', '1'];

    /**
     * @var array Valores permitidos para **FALSE**
     */
    public const VALORES_BOOLEANOS_FALSE = ['off', 'false', 'no', 'disabled', '0'];

    /**
     * Constructor
     *
     * @param array  $datos  Datos de la solicitud
     * @param string $metodo Nombre del método de la solicitud
     */
    public function __construct(private array $datos, private string $metodo)
    {
    }

    /**
     * Crea una instancia de la clase desde un array
     *
     * @param array  $datos
     * @param string $metodo
     *
     * @return SolicitudDeMetodo
     */
    public static function desdeArray(array $datos, string $metodo): self
    {
        return new self($datos, $metodo);
    }

    /**
     * Verifica si existe el elemento con la clave indicada
     *
     * @param string $clave
     *
     * @return bool
     */
    public function existe(string $clave): bool
    {
        return isset($this->datos[$clave]);
    }

    /**
     * Obtiene un string desde la solicitud
     *
     * @param string $clave Clave asociado
     * @param string $defecto Valor por defecto devuelto si no existe ningún elemento asociado
     *
     * @return string
     */
    public function obtenerString(string $clave, string $defecto = ''): string
    {
        if( !isset($this->datos[$clave]) ) {
            return $defecto;
        }
        if( !is_string($this->datos[$clave]) ) {
            throw new ErrorDeTipo($clave, 'array', $this->metodo);
        }
        return $this->datos[$clave];
    }

    /**
     * Obtiene un int desde la solicitud
     *
     * @param string $clave Clave asociado
     * @param int    $defecto Valor por defecto devuelto si no existe ningún elemento asociado
     *
     * @return int
     */
    public function obtenerInt(string $clave, int $defecto = 0): int
    {
        if( !isset($this->datos[$clave]) ) {
            return $defecto;
        }
        if( !is_numeric($this->datos[$clave]) ) {
            throw new ErrorDeTipo($clave, 'int', $this->metodo);
        }
        return (int)$this->datos[$clave];
    }

    /**
     * Obtiene un array desde la solicitud
     *
     * @param string $clave   Clave asociado
     * @param array  $defecto Valor por defecto devuelto si no existe ningún elemento asociado
     *
     * @return array
     */
    public function obtenerArray(string $clave, array $defecto = []): array
    {
        if( !isset($this->datos[$clave]) ) {
            return $defecto;
        }
        if( !is_array($this->datos[$clave]) ) {
            throw new ErrorDeTipo($clave, 'array', $this->metodo);
        }
        return (array)$this->datos[$clave];
    }

    /**
     * Obtiene un bool desde la solicitud
     *
     * @param string $clave   Clave asociado
     * @param bool   $defecto Valor por defecto devuelto si no existe ningún elemento asociado
     *
     * @return bool
     *
     * @see SolicitudDeMetodo::VALORES_BOOLEANOS_TRUE para ver los valores aceptados para true
     * @see SolicitudDeMetodo::VALORES_BOOLEANOS_FALSE para ver los valores aceptados para false
     */
    public function obtenerBool(string $clave, bool $defecto = false): bool
    {
        if( !isset($this->datos[$clave]) ) {
            return $defecto;
        }
        if( !is_bool($this->datos[$clave]) ) {
            if( is_string($this->datos[$clave]) ) {
                $dato = strtolower($this->datos[$clave]);
                if( in_array($dato, self::VALORES_BOOLEANOS_TRUE) ) return true;
                if( in_array($dato, self::VALORES_BOOLEANOS_FALSE) ) return false;
            }
            throw new ErrorDeTipo($clave, 'bool', $this->metodo);
        }
        return (bool)$this->datos[$clave];
    }

}
