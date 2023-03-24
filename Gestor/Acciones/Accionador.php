<?php

namespace Gof\Gestor\Acciones;

use Gof\Gestor\Acciones\Interfaz\Accion;
use Gof\Interfaz\Lista;

/**
 * Gestor de acciones sobre elementos de una lista
 *
 * Ejecuta una acción por cada elemento de la lista de datos.
 *
 * @package Gof\Gestor\Acciones
 */
class Accionador
{
    /**
     * @var Lista Lista de datos con los elementos.
     */
    private Lista $datos;

    /**
     * @var Accion Acción que se ejecutará por cada elemento.
     */
    private Accion $accion;

    /**
     * Constructor
     *
     * @param Lista  $datos  Lista con los datos.
     * @param Accion $accion Instancia de la acción a cometer por cada elemento.
     */
    public function __construct(Lista $datos, Accion $accion)
    {
        $this->datos = $datos;
        $this->accion = $accion;
    }

    /**
     * Ejecuta la acción por cada elemento
     *
     * Ejecuta la acción almacenada por cada elemento existente y le pasa como argumentos el
     * identificador del elemento (clave) y su valor asociado.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function accionar(): bool
    {
        foreach( $this->datos->lista() as $identificador => $elemento ) {
            $this->accion->accionar($elemento, $identificador);
        }

        return true;
    }

    /**
     * Ejecuta la acción sobre un único elemento
     *
     * @param mixed $elemento Elemento a pasar al accionador.
     * @param string $identificador Clave o identificador del elemento.
     */
    public function accionarEn(mixed $elemento, string $identificador): mixed
    {
        return $this->accion->accionar($elemento, $identificador);
    }

    /**
     * Lista de datos
     *
     * @param ?Lista $datos Nueva lista de datos o **null** para obtener la actual.
     *
     * @return Lista Devuelve la lista de datos actual.
     */
    public function datos(?Lista $datos = null): Lista
    {
        return $datos === null ? $this->datos : $this->datos = $datos;
    }

}
