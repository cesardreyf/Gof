<?php

namespace Gof\Gestor\Acciones;

use Gof\Gestor\Acciones\Interfaz\Accion;

/**
 * Gestor de acciones
 *
 * Clase que proporciona una funcionalidad para ejecutar una acción sobre un elemento. En
 * otras palabras, permite abstraer el tratamiento de datos sobre un objeto.
 *
 * @package Gof\Gestor\Acciones
 */
class AccionadorSimple
{
    /**
     * @var Accion Acción que se ejecutará por cada elemento.
     */
    private Accion $accion;

    /**
     * Constructor
     *
     * @param Accion $accion Instancia de la acción
     */
    public function __construct(Accion $accion)
    {
        $this->accion = $accion;
    }

    /**
     * Ejecuta la acción sobre un único elemento
     *
     * @param mixed  $elemento      Elemento a pasar al accionador.
     * @param string $identificador Clave o identificador del elemento.
     *
     * @return mixed Devuelve el resultado devuelto por la acción.
     */
    public function accionarEn(mixed $elemento, string $identificador): mixed
    {
        return $this->accion->accionar($elemento, $identificador);
    }

}
