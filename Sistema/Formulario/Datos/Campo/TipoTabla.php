<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Datos\Campo\TipoTabla\ErrorAsociativo;
use Gof\Sistema\Formulario\Gestor\Asignar\AsignarCampo;
use Gof\Sistema\Formulario\Gestor\Campos\ValidarCampo;
use Gof\Sistema\Formulario\Gestor\Campos\ValidarExtras;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Campo de tipo tabla
 *
 * Campo para validar datos de tipo tabla que contengan filas y columnas.  El
 * valor debe ser un array de arrays. Cada elemento del valor debe ser una fila
 * mientras que el valor de cada fila debe ser un array cuyos índices deben ser
 * el nombre de la columna y su valor el esperado según la declaración del
 * mismo. Las columnas se definen por su nombre y el tipo esperado.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
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
     * @var array<string, Campo> Lista de columnas y tipos correspondientes.
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
        $this->error = new ErrorAsociativo();
    }

    /**
     * Agrega una nueva columna a la lista
     *
     * Crea una columna en la lista de columnas y asocia un campo del tipo
     * esperado.  El campo será usado para validar los valores de las columnas
     * de cada fila.
     *
     * @param string $nombre Nombre de la columna.
     * @param int    $tipo   Tipo de datos que aceptará la columna.
     *
     * @return Campo Instancia del campo que será usado para validar.
     *
     * @see Tipos
     */
    public function columna(string $nombre, int $tipo): Campo
    {
        if( !isset($this->columnas[$nombre]) ) {
            $this->columnas[$nombre] = AsignarCampo::segunTipo($nombre, $tipo);
        }

        return $this->columnas[$nombre];
    }

    /**
     * Obtiene la lista de columnas interna
     *
     * Devuelve un array con los nombres de las columnas como claves y sus
     * tipos como valores.
     *
     * @return array<string, Campo> Devuelve un array con todas las columnas.
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

        $columnasObligatorias = array_filter($this->columnas, function(Campo $campo) {
            return $campo->obligatorio();
        });

        $filasQueNoSonValidas = array_filter($this->valor(), function($fila, $nFila) use ($columnasObligatorias) {
            if( is_array($fila) === false ) {
                $this->reportarError($nFila, self::FILAS_INVALIDAS, Errores::ERROR_FILAS_INVALIDAS);
                return true;
            }

            // Si no hay columnas que validar...
            if( empty($this->columnas) ) {
                return false;
            }

            if( empty(array_diff_key($columnasObligatorias, $fila)) === false ) {
                $this->reportarError($nFila, self::COLUMNAS_FALTAN, Errores::ERROR_COLUMNAS_FALTAN);
                return true;
            }

            $columnasInvalidas = false;
            $validarCampo = new ValidarCampo();

            foreach( $this->columnas as $nombreDeLaColumna => $campo ) {
                $campo->valor = $fila[$nombreDeLaColumna];

                if( $validarCampo->validar($campo) && ValidarExtras::validar($campo) ) {
                    continue;
                }

                $codigoDelError = $campo->error()->codigo();

                // Si el campo es opcional y el error es de tipo campo vacío o
                // campo inexistente ignora el error y continúa con las
                // siguientes validaciones
                if( !$campo->obligatorio() && ($codigoDelError === Errores::ERROR_CAMPO_VACIO || $codigoDelError === Errores::ERROR_CAMPO_INEXISTENTE) ) {
                    $campo->error()->limpiar();
                    continue;
                }

                $this->reportarError($nFila, $campo->error()->mensaje(), $campo->error()->codigo(), $nombreDeLaColumna);
                $campo->error()->limpiar();
                $columnasInvalidas = true;
            }

            return $columnasInvalidas;
        }, ARRAY_FILTER_USE_BOTH);

        return empty($filasQueNoSonValidas);
    }

    /**
     * Agrega un error a la lista de errores del campo
     *
     * @param string $clave     Clave al que se asociará el error.
     * @param string $mensaje   Mensaje de error.
     * @param int    $codigo    Código de error.
     * @param string ...$claves
     *
     * @access private
     */
    private function reportarError(string $clave, string $mensaje, int $codigo, string ...$claves)
    {
        $this->error->clave($clave, ...$claves);
        $this->error->codigo($codigo);
        $this->error->mensaje($mensaje);
    }

}
