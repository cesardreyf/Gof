<?php

namespace Gof\Sistema\Propiedades\Simple\Operaciones;

use Gof\Gestor\Acciones\Interfaz\Accion;
use Gof\Gestor\Propiedades\Propiedad;
use Gof\Sistema\Propiedades\Simple\Condicion\CondicionNinguna;
use Gof\Sistema\Propiedades\Simple\Interfaz\Condicion;

/**
 * Gestor de operación condicional
 *
 * Clase encargada de gestionar la operación sobre una lista de propiedades siempre y cuando
 * se cumpla una condición, caso contrario no se ejecutará la operación.
 *
 * La clase extiende las funcionalidades de **OperacionSimple**. Para más información ver
 * la documentación sobre dicha clase.
 *
 * @see OperacionSimple
 *
 * @package Gof\Sistema\Propiedades\Simple\Operaciones
 */
class OperacionCondicional extends OperacionSimple
{
    /**
     * @var int Identificador reservado para almacenar los errores internos del operador.
     */
    const IDENTIFICADOR_RESERVADO = '-1';

    /**
     * @var int Error que indica que la condición no se cumplió.
     */
    const CONDICION_INCUMPLIDA = -2;

    /**
     * @var Condicion Instancia de la condición.
     */
    private Condicion $condicional;

    /**
     * Constructor
     *
     * @param Propiedad  $lista     Gestor de propiedades (Lista).
     * @param Accion     $accion    Gestor de la acción.
     * @param ?Condicion $condicion Instancia de la condición a cumplirse.
     */
    public function __construct(Propiedad $lista, Accion $accion, ?Condicion $condicion = null)
    {
        parent::__construct($lista, $accion);
        $this->segun($condicion === null ? new CondicionNinguna() : $condicion);
    }

    /**
     * Opera sobre las propiedades si se cumple con la condición.
     *
     * Previamente al procesamiento de las propiedades verifica si la condición devuelve **true**,
     * caso contrario no operará sobre las propiedades y se devolverá un error.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function operar(): bool
    {
        if( $this->condicional->condicion() === false ) {
            $this->errores->agregar(self::CONDICION_INCUMPLIDA, self::IDENTIFICADOR_RESERVADO);
            return false;
        }

        return parent::operar();
    }

    /**
     * Obtiene o define la condición
     *
     * @param ?Condicion $condicion Condición a cumplir o **null** para obtener el actual.
     *
     * @return Condicion Devuelve una instancia de la condición actual del operador.
     */
    public function segun(?Condicion $condicion = null): Condicion
    {
        return $condicion === null ? $this->condicional : $this->condicional = $condicion;
    }

}
