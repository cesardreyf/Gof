<?php

namespace Gof\Sistema\MVC\Inters;

use Gof\Gestor\Autoload\Autoload;
use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Inters\Cargador\Cargador;
use Gof\Sistema\MVC\Inters\Contenedor\Contenedor;

/**
 * Gestor de inters
 *
 * Módulo del sistema MVC encargado de gestionar los inters.
 *
 * Los inters son procesos ejecutables de la aplicación del sistema MVC. La
 * aplicación ejecuta los procesos por órden de prioridad. Los inters ocupan la
 * prioridad Media. Esto quiere decir que se ejecutarán después de los procesos
 * esenciales del sistema MVC y antes de la ejecución del controlador (el cual
 * ocupa un proceso de prioridad Baja).
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

    /**
     * Carga los inters almacenados en un contenedor
     *
     * Recorre la lista de nombres de clases de inters alojados en el
     * contenedor, crea las instancias y luego los agrega al gestor de
     * procesos de la aplicación del sistema MVC.
     *
     * Delega la creación de los objetos de los inters al gestor de autoload.
     * Delega la inserción de los procesos a la función agregar.
     *
     * @param Contenedor $inters   Instancia del contenedor.
     * @param Autoload   $autoload Instancia del gestor de autoload.
     *
     * @see Inters::agregar()
     */
    public function cargar(Contenedor $inters, Autoload $autoload)
    {
        $cargador = new Cargador($autoload);
        foreach( $cargador->cargar($inters) as $inter ) {
            $this->agregar($inter);
        }
    }

}
