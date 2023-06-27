<?php

namespace Gof\Sistema\Formulario\Datos\Campo;

use Gof\Datos\Lista\Texto\ListaDeTextos;
use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\ErroresMensaje;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Mediador\Campo\Error;

/**
 * Campo de tipo select
 *
 * Campo de tipo select que permite un conjunto de valores predeterminado.
 *
 * @package Gof\Sistema\Formulario\Datos\Campo
 */
class TipoSelect extends Campo
{
    /**
     * @var string Mensaje de error para cuando la opción no forme parte del conjunto de opciones válidas.
     */
    public const OPCION_INVALIDA = 'La opción seleccionada no es válida';

    /**
     * @var string[] Conjunto de opciones válidas para el campo
     */
    private array $opciones = [];

    /**
     * Constructor
     *
     * @param string $clave Nombre del campo.
     */
    public function __construct(string $clave)
    {
        parent::__construct($clave, Tipos::TIPO_SELECT);
    }

    /**
     * Agrega una nueva opción
     *
     * Agrega una opción al conjunto de opciones válidas que puede tener el
     * valor del campo.
     *
     * @param string $nombre Nombre de la opción.
     */
    public function opcion(string $nombre)
    {
        $this->opciones[] = $nombre;
    }

    /**
     * Conjunto de opciones válidas
     *
     * @return string[] Devuelve un array con todas las opciones válidas.
     */
    public function opciones(): array
    {
        return $this->opciones;
    }

    /**
     * Agrega una lista de opciones válidas
     *
     * Agrega a la lista interna las opciones desde una **ListaDeTextos**.
     *
     * @param ListaDeTextos $opciones Instancia de una lista de string.
     *
     * @see ListaDeTextos
     */
    public function agregarOpcionesDesdeLista(ListaDeTextos $opciones)
    {
        foreach( $opciones->lista() as $nombreDeLaOpcion ) {
            $this->opcion($nombreDeLaOpcion);
        }
    }

    /**
     * Valida que el valor del campo forme parte del conjunto de opciones
     *
     * Determina si el valor es un string y si este forma parte del conjunto de
     * opciones válidas almacenadas internamente.
     *
     * @return ?bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function validar(): ?bool
    {
        if( is_string($this->valor()) === false ) {
            Error::reportar($this, ErroresMensaje::NO_ES_SELECT, Errores::ERROR_NO_ES_SELECT);
            return false;
        }

        if( $this->valor() === '' && !$this->opcionEsValida('') ) {
            Error::reportar($this, ErroresMensaje::CAMPO_VACIO, Errores::ERROR_CAMPO_VACIO);
            return false;
        }

        if( $this->opcionEsValida($this->valor()) === false ) {
            Error::reportar($this, self::OPCION_INVALIDA, Errores::ERROR_OPCION_INVALIDA);
            return false;
        }

        return true;
    }

    /**
     * Valida si la opción pasada es válida
     *
     * Verifica si la opción pasada forma parte del conjunto de opciones válidas.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     *
     * @access protected
     */
    protected function opcionEsValida(string $opcion): bool
    {
        return in_array($opcion, $this->opciones);
    }

}
