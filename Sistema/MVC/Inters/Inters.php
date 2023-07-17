<?php

namespace Gof\Sistema\MVC\Inters;

use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Gestor de inters
 *
 * Módulo encargado de gestionar los inters: procesos que se ejecutan antes del
 * controlador.
 *
 * @package Gof\Sistema\MVC\Inters
 */
class Inters
{
    /**
     * Instancia del gestor de procesos de la aplicación
     *
     * @var Procesos
     */
    private Procesos $procesos;

    /**
     * Constructor
     *
     * @param Procesos $procesos Instancia del gestor de procesos de la aplicación.
     */
    public function __construct(Procesos $procesos)
    {
        $this->procesos = $procesos;
    }

    /**
     * Agregar un inter
     *
     * Agrega un inter a la lista de procesos que se ejecutarán antes del
     * controlador.
     *
     * @param Ejecutable $inter Instancia del inter.
     */
    public function agregar(Ejecutable $inter)
    {
        $this->procesos->agregar($inter, Prioridad::Media);
    }

    /**
     * Agrega una lista de inters
     *
     * @param Lista $inters Lista de inters.
     *
     * @see Inters::agregar()
     */
    public function agregarLista(Lista $inters)
    {
        $lista = $inters->lista();
        array_walk($lista, function(Ejecutable $inter) {
            $this->agregar($inter);
        });
    }

}
