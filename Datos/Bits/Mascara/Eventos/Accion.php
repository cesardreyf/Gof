<?php

namespace Gof\Datos\Bits\Mascara\Eventos;

/**
 * Clase encargada de indicar qué hacer cuando el evento ocurra
 *
 * Almacena una función anónima el cual será llamado cuando el evento se produzca.
 *
 * @package Gof\Datos\Bits\Mascara\Eventos
 */
class Accion
{
    /**
     * @var int Bits necesarios para activar el evento al cumplirse la condición
     */
    private int $bits;

    /**
     * @var array Referencia a la lista de eventos
     */
    private array $lista;

    /**
     * @var int Condicion para el evento
     */
    private $condicion;

    /**
     * Constructor
     *
     * @param int    $condicion Condición para el evento
     * @param int    $bits      Máscara de bits necesarios para activar el evento al cumplirse la condición
     * @param array &$lista     Referencia a la lista de eventos
     */
    public function __construct(int $condicion, int $bits, array& $lista)
    {
        $this->bits = $bits;
        $this->lista =& $lista;
        $this->condicion = $condicion;
    }

    /**
     * Almacena la función anónima como evento a suceder al cumplirse las condiciones
     *
     * @param callable $funcion Función anónima
     */
    public function haz(callable $funcion)
    {
        $this->lista[$this->condicion][$this->bits][] = $funcion;
    }

}
