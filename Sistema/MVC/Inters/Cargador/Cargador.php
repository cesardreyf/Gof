<?php

namespace Gof\Sistema\MVC\Inters\Cargador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Inters\Contenedor\Contenedor;

/**
 * Cargador de inters
 *
 * Clase encargada de crear inters alojados en un contenedor y devolverlos en
 * un array.
 *
 * @package Gof\Sistema\MVC\Inters\Cargador
 */
class Cargador
{
    /**
     * Gestor del autoload para crear las instancias de las clases
     *
     * @var Autoload
     */
    private Autoload $autoload;

    /**
     * Constructor
     *
     * @param Autoload $autoload Instancia del gestor de autoload.
     */
    public function __construct(Autoload $autoload)
    {
        $this->autoload = $autoload;
    }

    /**
     * Carga todos los inters alojados en el contenedor
     *
     * Crea las instancias de las clases almacenadas en el contenedor, los
     * almacena en un array y lo devuelve.
     *
     * @param Contenedor $contenedor Contenedor de inters.
     *
     * @return Ejecutable[]
     */
    public function cargar(Contenedor $contenedor): \Generator
    {
        foreach( $contenedor->obtener() as $nombreCompletoDelInter ) {
            $inter = $this->autoload->instanciar($nombreCompletoDelInter);
            // TAREA: Lanzar excepción si es null o si no es ejecutable
            yield $inter;
        }
    }

}
