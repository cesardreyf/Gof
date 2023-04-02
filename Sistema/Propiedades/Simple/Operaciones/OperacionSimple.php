<?php

namespace Gof\Sistema\Propiedades\Simple\Operaciones;

use Gof\Datos\Errores\ErrorAsociativo;
use Gof\Gestor\Acciones\Accionador;
use Gof\Gestor\Acciones\Interfaz\Accion;
use Gof\Gestor\Propiedades\Propiedad;
use Gof\Interfaz\Errores\Errores;

/**
 * Gestor de operación simple
 *
 * Clase que gestiona la operación sobre las listas de propiedades, lo que incluye el tratamiento
 * de los datos y su gestión de errores. Básicamente se encarga de recorrer una lista y ejecutar
 * un tratamiento sobre cada elemento, guardando los errores en una lista.
 *
 * @package Gof\Sistema\Propiedades\Simple\Operaciones
 */
class OperacionSimple
{
    /**
     * @var int Error que se devuelve cuando el resultado(tipo) del accionar no es el esperado.
     */
    const RESULTADO_INESPERADO = -1;

    /**
     * @var Accion $accion Acción que se ejecutará sobre cada propiedad de la lista al operar.
     */
    protected Accion $accion;

    /**
     * @var Propiedad Gestor de propiedades.
     */
    protected Propiedad $propiedades;

    /**
     * @var ErrorAsociativo Lista de errores.
     */
    protected ErrorAsociativo $errores;

    /**
     * Constructor
     *
     * @param Propiedad $listaDePropiedades Gestor de lista de las propiedades.
     * @param Accion    $accion             Acción que se ejecutará por cada elemento de la lista.
     */
    public function __construct(Propiedad $listaDePropiedades, Accion $accion)
    {
        $this->propiedades = $listaDePropiedades;
        $this->errores = new ErrorAsociativo();
        $this->accion = $accion;
    }

    /**
     * Opera sobre el conjunto de elementos reservados
     *
     * Recorre la lista de propiedades y ejecuta la **acción** por cada elemento. Si ocurre
     * un error se almacena el mismo en la lista de errores interno del módulo, respetando
     * el identificador de la propiedad.
     *
     * La gestión de los errores consiste en validar que la **acción** ejecutada por cada
     * elemento devuelve un valor diferente de cero. Si el valor devuelto es cero se asume
     * que no hubieron errores durante la ejecución.
     *
     * @return bool Devuelve **true** si no ocurrió ningún error, **false** de lo contrario.
     */
    public function operar(): bool
    {
        $estadoDeLaOperacion = true;
        foreach( $this->propiedades->lista() as $identificador => $propiedad ) {
            $resultado = $this->tratarPropiedad($propiedad, $identificador);

            if( $this->procesarErrores($resultado, $identificador) === true ) {
                $estadoDeLaOperacion = false;
            }
        }

        return $estadoDeLaOperacion;
    }

    /**
     * Trata las propiedades
     *
     * Ejecuta la **acción** del módulo sobre la propiedad.
     *
     * @param mixed  $propiedad     Propiedad a pasarle a la **accion**.
     * @param string $identificador Clave asociada a la propiedad.
     *
     * @return mixed Devuelve lo que retorna el **accionar**.
     */
    protected function tratarPropiedad(mixed $propiedad, string $identificador)
    {
        return $this->accion->accionar($propiedad, $identificador);
    }

    /**
     * Procesa los errores (si los hay) y los asocia a su identificador
     *
     * @param mixed  $resultado     Resultado a validar.
     * @param string $identificador Identificador al que se asociarán los errores.
     *
     * @return bool Devuelve **true** si hubo errores o **false** de lo contrario.
     */
    protected function procesarErrores(mixed $resultado, string $identificador): bool
    {
        if( $resultado !== 0 ) {
            $this->errores->agregar(is_int($resultado) ? $resultado : self::RESULTADO_INESPERADO, $identificador);
            return true;
        }

        return false;
    }

    /**
     * Conjunto de propiedades
     *
     * @return Propiedad Devuelve el gestor de propiedades.
     */
    public function propiedades(): Propiedad
    {
        return $this->propiedades;
    }

    /**
     * Conjunto de errores
     *
     * @return Errores Devuelve el submódulo de errores.
     */
    public function errores(): Errores
    {
        return $this->errores;
    }

}
