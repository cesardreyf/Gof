<?php

namespace Gof\Gestor\Formulario\Datos;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Datos\Errores\ErrorAsociativo;
use Gof\Interfaz\Bits\Mascara;
use Gof\Interfaz\Errores\Errores;

/**
 * Gestor de obtención de datos de formularios
 *
 * Clase encargada de obtener los valores de los elementos que conforman los datos de un
 * formulario. Estos datos se obtienen de arrays pasados como argumento por el constructor y
 * que pueden ser algunas de las siguientes: _GET, _POST, _PUT, _DELETE.
 *
 * @package Gof\Formulario\Datos
 */
class DatosSimples
{
    /**
     * @var int Configuración para validar los campos vacíos
     */
    const VALIDAR_CAMPOS_VACIOS = 1;

    /**
     * Configuración para interpretar un campo vacío como un valor booleano igual a **false**
     *
     * Cuando se obtiene un valor booleano si está activo esta directiva y el campo existe pero
     * está vacío se interpretará como un valor booleano igual a **false**. Caso contrario
     * se devolvería un valor **null**.
     *
     * @var int
     *
     * @see Datos::booleano()
     */
    const INTERPRETAR_BOOLEANO_VACIO = 2;

    /**
     * Directiva de configuración para que se interprete los números enteros como números float
     *
     * Al obtener un número float se interpretará los números enteros, y las cadenas con números
     * enteros, como números flotantes.
     *
     * @var int
     *
     * @see Datos::flotante()
     */
    const INTERPRETAR_ENTEROS_COMO_FLOAT = 4;

    /**
     * @var int Directiva de configuración para interpretar otros tipos de valores desde una cadena
     */
    const INTERPRETAR_CADENAS = 8;

    // const VALIDAR_CAMPOS_VACIOS_EN_STRING = 16;

    /**
     * @var int Máscara de bits con la configuración por defecto del gestor
     */
    const CONFIGURACION_POR_DEFECTO = self::INTERPRETAR_CADENAS
                                    | self::INTERPRETAR_BOOLEANO_VACIO;

    /**
     * @var int Indica que el campo del formulario está vacío
     */
    const CAMPO_VACIO = 100;

    /**
     * @var int Indica que el campo solicitado no existe
     */
    const CAMPO_INEXISTENTE = 101;

    /**
     * @var int Indica que el formato no es válido
     */
    const FORMATO_INCORRECTO = 200;

    /**
     * @var int Indica que el tipo del valor recibido no es un Int
     */
    const FORMATO_INCORRECTO_INT = 201;

    /**
     * @var int Indica que el tipo del valor recibido no es un Float
     */
    const FORMATO_INCORRECTO_FLOAT = 202;

    /**
     * @var int Indica que el tipo del valor recibido no es un Bool
     */
    const FORMATO_INCORRECTO_BOOL = 203;

    /**
     * @var int Indica que el tipo del valor recibido no es un Array
     */
    const FORMATO_INCORRECTO_ARRAY = 204;

    /**
     * @var int Indica que el tipo del valor esperado no es un String
     */
    const FORMATO_INCORRECTO_STRING = 205;

    /**
     * @var array $datos Lista de datos del formulario
     */
    protected $datos;

    /**
     * @var ErrorNumerico $errores Lista de errores
     */
    protected $errores;

    /**
     * @var MascaraDeBits Máscara de bits con la configuración del gestor
     */
    protected $configuracion;

    /**
     * Constructor
     *
     * @param array $datos         Array desde donde se tomarán los campos del formulario
     * @param int   $configuracion Máscara de bits con la configuración del gestor
     */
    public function __construct(array $datos, int $configuracion = self::CONFIGURACION_POR_DEFECTO)
    {
        $this->datos = $datos;
        $this->errores = new ErrorAsociativo();
        $this->configuracion = new MascaraDeBits($configuracion);
    }

    /**
     * Valida si existe un elemento en la petición con la clave especificada
     *
     * @param string $clave Clave del elemento a validar
     *
     * @return bool Devuelve **true** si el elemento existe, **false** si no.
     */
    public function existe(string $clave): bool
    {
        return isset($this->datos[$clave]);
    }

    /**
     * Valida si el array de datos está vacía
     *
     * @return bool Devuelve **true** si el array de datos está vacía o **false** de lo contrario.
     */
    public function vacio(): bool
    {
        return empty($this->datos);
    }

    /**
     * Valida y obtiene el valor del elemento solicitado
     *
     * @param string $clave Clave del elemento a obtener.
     *
     * @return ?string Devuelve una cadena o **NULL** en caso de error.
     *
     * @see Datos::errores() para más información sobre los errores en caso de ocurrir.
     */
    public function cadena(string $clave): ?string
    {
        if( $this->existe($clave) === false ) {
            $this->agregarError($clave, self::CAMPO_INEXISTENTE);
            return null;
        }

        if( is_string($this->datos[$clave]) === false ) {
            $this->agregarError($clave, self::FORMATO_INCORRECTO_STRING);
            return null;
        }

        if( /*$this->configuracion->activados(self::VALIDAR_CAMPOS_VACIOS_EN_STRING) &&*/ mb_strlen($this->datos[$clave]) === 0 ) {
            $this->agregarError($clave, self::CAMPO_VACIO);
            return null;
        }

        return $this->datos[$clave];
    }

    /**
     * Valida y obtiene el valor Int del elemento solicitado
     *
     * @param string $clave Clave del elemento a obtener.
     *
     * @return ?int Devuelve un valor Int o **NULL** en caso de error.
     *
     * @see Datos::errores() para más información sobre los errores en caso de ocurrir.
     */
    public function entero(string $clave): ?int
    {
        if( $this->existe($clave) === false ) {
            $this->agregarError($clave, self::CAMPO_INEXISTENTE);
            return null;
        }

        if( is_int($this->datos[$clave]) ) {
            return $this->datos[$clave];
        }

        if( $this->configuracion->activados(self::INTERPRETAR_CADENAS) && is_string($this->datos[$clave]) ) {
            if( mb_strlen($this->datos[$clave]) === 0 ) {
                $this->agregarError($clave, self::CAMPO_VACIO);
                return null;
            }

            if( preg_match('/^-?\d+$/', $this->datos[$clave]) === 1 ) {
                return $this->datos[$clave];
            }
        }

        $this->agregarError($clave, self::FORMATO_INCORRECTO_INT);
        return null;
    }

    /**
     * Valida y obtiene el valor Float del elemento solicitado
     *
     * @param string $clave Clave del elemento a obtener.
     *
     * @return ?float Devuelve un valor Float o **NULL** en caso de error.
     *
     * @see Datos::errores() para más información sobre los errores en caso de ocurrir.
     */
    public function flotante(string $clave): ?float
    {
        if( $this->existe($clave) === false ) {
            $this->agregarError($clave, self::CAMPO_INEXISTENTE);
            return null;
        }

        if( $this->datos[$clave] === 0 ) {
            return 0;
        }

        if( is_float($this->datos[$clave])
        ||( $this->configuracion->activados(self::INTERPRETAR_ENTEROS_COMO_FLOAT) 
        &&  is_int($this->datos[$clave])) ) {
            return $this->datos[$clave];
        }

        if( $this->configuracion->activados(self::INTERPRETAR_CADENAS) && is_string($this->datos[$clave]) ) {
            if( empty($this->datos[$clave]) ) {
                $this->agregarError($clave, self::CAMPO_VACIO);
                return null;
            }

            if( $this->configuracion->activados(self::INTERPRETAR_ENTEROS_COMO_FLOAT) ) {
                $expresion = '/^-?(\d+|\d*\.\d+)$/';
            } else {
                $expresion = '/^-?\d*\.\d+$/';
            }

            if( preg_match($expresion, $this->datos[$clave]) === 1 ) {
                return $this->datos[$clave];
            }
        }

        $this->agregarError($clave, self::FORMATO_INCORRECTO_FLOAT);
        return null;
    }

    /**
     * Valida y obtiene el valor Bool del elemento solicitado
     *
     * Si en configuración está activo **INTERPRETAR_BOOLEANO_VACIO** y el campo existe pero
     * está vacío, se interpretará como un valor **false**.
     *
     * **NOTA:** Esta función puede devolver un valor que se puede interpretar como **false**
     * cuando en realidad estaría indicando un error. Para solucionar esto debe usar **===**.
     *
     * @param string $clave Clave del elemento a obtener.
     *
     * @return ?bool Devuelve un valor Bool o **NULL** en caso de error.
     *
     * @see Datos::errores() para más información sobre los errores en caso de ocurrir.
     */
    public function booleano(string $clave): ?bool
    {
        if( $this->existe($clave) === false ) {
            $this->agregarError($clave, self::CAMPO_INEXISTENTE);
            return null;
        }

        $valor = $this->datos[$clave];
        if( is_bool($valor) ) {
            return $valor;
        }

        if( $this->configuracion->activados(self::INTERPRETAR_CADENAS) && is_string($valor) ) {
            if( mb_strlen($valor) === 0 ) {
                if( $this->configuracion->activados(self::INTERPRETAR_BOOLEANO_VACIO) ) {
                    return false;
                }

                $this->agregarError($clave, self::CAMPO_VACIO);
                return null;
            }

            if( $valor === '1' || mb_strtolower($valor) === 'on') {
                return true;
            }

            if( $valor === '0' || mb_strtolower($valor) === 'off') {
                return false;
            }
        }

        $this->agregarError($clave, self::FORMATO_INCORRECTO_BOOL);
        return null;
    }

    /**
     * Obtiene la lista de errores
     *
     * @return Errores Devuelve la instancia de la lista de errores
     */
    public function errores(): Errores
    {
        return $this->errores;
    }

    /**
     * Configuración interna
     *
     * @return Mascara Devuelve un gestor de máscara de bits para la configuración interna
     */
    public function configuracion(): Mascara
    {
        return $this->configuracion;
    }

    protected function agregarError(string $clave, int $error)
    {
        $this->errores->agregar($clave, $error);
    }

}
