<?php

namespace Gof\Sistema\Formulario\Interfaz;

/**
 * Interfaz con constantes predefinidas de errores
 *
 * Interfaz que contiene constantes con códigos de errores comunes para el
 * sistema de formulario.
 *
 * @package Gof\Sistema\Formulario\Interfaz
 */
interface Errores
{
    /**
     * @var int Indica que el campo no existe en los datos recibidos
     */
    public const ERROR_CAMPO_INEXISTENTE = 1;

    /**
     * @var int Indica que el valor del campo no es un string válido
     */
    public const ERROR_NO_ES_STRING = 200;

    /**
     * @var int Indica que el valor del campo no es un int válido
     */
    public const ERROR_NO_ES_INT = 201;

    /**
     * @var int Indica que el valor del campo no es un array válido
     */
    public const ERROR_NO_ES_ARRAY = 202;

    /**
     * @var int Indica que el valor del campo no es una tabla válida
     */
    public const ERROR_NO_ES_TABLA = 203;

    /**
     * @var int Indica que el valor del campo no es un float
     */
    public const ERROR_NO_ES_FLOAT = 204;

    /**
     * @var int Indica que el valor del campo no es un string
     */
    public const ERROR_NO_ES_SELECT = 205;

    /**
     * @var int Indica que el campo está vacío y no contiene nada
     */
    public const ERROR_CAMPO_VACIO = 300;

    /**
     * @var int Indica que el valor del campo es menor que el límite mínimo
     */
    public const ERROR_LIMITE_MINIMO_NO_ALCANZADO = 1100;

    /**
     * @var int Indica que el valor del campo es mayor que el límite máximo
     */
    public const ERROR_LIMITE_MAXIMO_EXCEDIDO = 1101;

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
     * @var int Código de error que indica que la opción seleccionada no es válida.
     */
    public const ERROR_OPCION_INVALIDA = 20001;
}
