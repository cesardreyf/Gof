<?php

namespace Gof\Sistema\Reportes\Plantilla;

use Gof\Sistema\Reportes\Interfaz\Plantilla;
use Throwable;

/**
 * Plantilla empleada por el sistema de reportes para los errores
 *
 * Plantilla encargada de traducir los datos recibidos por el sistema de reportes.
 *
 * @package Gof\Sistema\Reportes\Plantilla
 */
class Errores implements Plantilla
{
    /**
     * @var string Almacena el mensaje traducido
     */
	private string $mensaje = '';

    /**
     * @var array<int, string> Lista de tipos de errores
     */
    protected array $tipoDeErrores = [
            E_ERROR             => 'E_ERROR'
        ,   E_WARNING           => 'E_WARNING'
        ,   E_PARSE             => 'E_PARSE'
        ,   E_NOTICE            => 'E_NOTICE'
        ,   E_CORE_ERROR        => 'E_CORE_ERROR'
        ,   E_CORE_WARNING      => 'E_CORE_WARNING'
        ,   E_COMPILE_ERROR     => 'E_COMPILE_ERROR'
        ,   E_COMPILE_WARNING   => 'E_COMPILE_WARNING'
        ,   E_USER_ERROR        => 'E_USER_ERROR'
        ,   E_USER_WARNING      => 'E_USER_WARNING'
        ,   E_USER_NOTICE       => 'E_USER_NOTICE'
        ,   E_STRICT            => 'E_STRICT'
        ,   E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR'
        ,   E_DEPRECATED        => 'E_DEPRECATED'
        ,   E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    ];

    /**
     * Mensaje traducido
     *
     * Mensaje traducido de los datos recibidos a través del método **traducir**.
     *
     * @return string Devuelve el mensaje traducido de los datos
     */
    public function mensaje(): string
    {
        return $this->mensaje;
    }

    /**
     * Traduce los datos recibidos
     *
     * Convierte un array de errores en un mensaje con los datos más importante del error.
     * El array de errores debe ser proporcionado por la función de PHP: error_get_last()
     * o ser un array asociativo y tener las siguientes claves: **type**, **line**,
     * **file** y **message**.
     *
     * @param array $datos Array de errores
     *
     * @return bool Devuelve **true** si la traducción fue exitosa **false** de lo contrario
     */
    public function traducir(array|Throwable $datos): bool
    {
        if( $datos instanceof Throwable ) {
            return false;
        }

        if( $this->datosNoCumpleConLosRequisitos($datos) ) {
            return false;
        }

        $this->mensaje = date('(d/m/Y) [G:i:s]')                                . "\n\n\t"
        .   'Tipo    ' . $this->tipoDeErrores[$datos['type']]                   . "\n\t"
        .   'Linea   ' . $datos['line']                                         . "\n\t"
        .   'Archivo ' . $datos['file']                                         . "\n\t"
        .   'Mensaje ' . str_replace("\n",   "\n\t        ", $datos['message']) . "\n\n";

        return true;
    }

    /**
     * Verifica si los datos no cumple con los requisitos esperados
     *
     * @param array $datos Datos a validar
     *
     * @return bool Devuelve **true** si los datos **no** tienen la información esperada.
     *
     * @access private
     */
    private function datosNoCumpleConLosRequisitos(array $datos): bool
    {
        $requisitos = ['type', 'line', 'file', 'message'];
        foreach( $requisitos as $requisito ) {
            if( isset($datos[$requisito]) === false ) {
                return true;
            }
        }

        if( is_int($datos['type'])    === false || is_int($datos['line'])       === false
         || is_string($datos['file']) === false || is_string($datos['message']) === false ) {
            return true;
        }

        if( isset($this->tipoDeErrores[$datos['type']]) === false ) {
            return true;
        }

        return false;
    }

}
