<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Gestor\Asignar\AsignarCampo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

class TipoTabla extends Campo
{
    /**
     * @var string Mensaje de error para cuando existan filas en la tabla que no sean arrays.
     */
    public const FILAS_INVALIDAS = 'Existen filas en la tabla que no son arrays';

    /**
     * @var string Mensaje de error para cuando algunas de las filas no contengan las columnas obligatorias.
     */
    public const COLUMNAS_FALTAN = 'Faltan columnas obligatorias';

    /**
     * @var string Mensaje de error para cuando una de las filas de la tabla tiene columnas cuyos tipos de valores no son válidos.
     */
    public const COLUMNAS_INVALIDAS = 'Alguna de las filas tiene columnas inválidas';

    /**
     * @var int Error que indica que existen filas dentro de la tabla que no son arrays.
     */
    public const ERROR_FILAS_INVALIDAS = 10001;

    /**
     * @var int Error que indica que una de las filas no contiene todas las columnas obligatorias.
     */
    public const ERROR_COLUMNAS_FALTAN = 10100;

    /**
     * @var int Error que indica que existen filas cuyas columnas no son válidas.
     */
    public const ERROR_COLUMNAS_INVALIDAS = 10101;

    /**
     * @vara array<string, int> Lista de columnas y tipos correspondientes.
     */
    private array $columnas = [];

    /**
     * Constructor
     *
     * @param string $clave Nombre del campo.
     */
    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_TABLA);
    }

    /**
     * Agrega una nueva columna a la lista
     *
     * @param string $nombre Nombre de la columna.
     * @param int    $tipo   Tipo de datos que aceptará la columna.
     */
    public function columna(string $nombre, int $tipo)
    {
        $this->columnas[$nombre] = $tipo;
    }

    /**
     * Obtiene la lista de columnas interna
     *
     * Devuelve un array con los nombres de las columnas como claves y sus
     * tipos como valores.
     *
     * @return array<string, int> Devuelve un array con todas las columnas.
     */
    public function obtenerColumnas(): array
    {
        return $this->columnas;
    }

    /**
     * Valida que el valor del campo corresponda con los datos esperados
     *
     * @return ?bool Devuelve **true** si son válidos o **false** de lo contrario.
     */
    public function validar(): ?bool
    {
        if( is_array($this->valor()) === false ) {
            Error::reportar($this, ErroresMensaje::NO_ES_TABLA, Errores::ERROR_NO_ES_TABLA);
            return false;
        }

        if( empty($this->valor()) ) {
            Error::reportar($this, ErroresMensaje::CAMPO_VACIO, Errores::ERROR_CAMPO_VACIO);
            return false;
        }

        array_walk($this->columnas, function($tipo, $columna) use (&$validarColumna) {
            $validarColumna[$columna] = AsignarCampo::segunTipo($columna, $tipo);
        });

        $filasQueNoSonValidas = array_filter($this->valor(), function($fila) use ($validarColumna) {
            if( is_array($fila) === false ) {
                Error::reportar($this, self::FILAS_INVALIDAS, self::ERROR_FILAS_INVALIDAS);
                return true;
            }

            // Si no hay columnas que validar...
            if( empty($validarColumna) ) {
                return false;
            }

            if( empty(array_diff_key($this->columnas, $fila)) === false ) {
                Error::reportar($this, self::COLUMNAS_FALTAN, self::ERROR_COLUMNAS_FALTAN);
                return true;
            }

            $columnasInvalidas = array_filter($fila, function($valor, $columna) use ($validarColumna) {
                $validarColumna[$columna]->valor = $valor;
                return !$validarColumna[$columna]->validar();
            }, ARRAY_FILTER_USE_BOTH);

            if( empty($columnasInvalidas) === false ) {
                Error::reportar($this, self::COLUMNAS_INVALIDAS, self::ERROR_COLUMNAS_INVALIDAS);
                return true;
            }

            return false;
        });

        return empty($filasQueNoSonValidas);
    }

}
