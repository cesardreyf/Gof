<?php

namespace Gof\Sistema\MVC\Inters\Cargador;

use Generator;
use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Interfaz\Ejecutable;
use Gof\Sistema\MVC\Inters\Cargador\Excepcion\InterInexistente;
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
     * @return Generator
     *
     * @throws InterInexistente si el autoload no pudo cargar correctamente la clase.
     * @throws InterInvalido si la clase del Inter no implementa la interfaz Ejecutable.
     *
     * @see Ejecutable
     */
    public function cargar(Contenedor $contenedor): Generator
    {
        foreach( $contenedor->obtener() as $clave => $nombreCompletoDelInter ) {
            $inter = $this->autoload->instanciar($nombreCompletoDelInter);
            $this->lanzarExcepcionSiElAutoloadNoPudoCargarLaClase($inter, $nombreCompletoDelInter);
            $this->lanzarExcepcionSiLaClaseCargadaNoImplementaLaInterfazEjecutable($inter, $nombreCompletoDelInter);
            yield $inter;
        }
    }

    /**
     * Verifica si la instancia recibida no es un valor nulo
     *
     * @param mixed      $inter
     * @param Contenedor $contenedor
     * @param string     $nombreDelInter
     *
     * @throws InterInexistente si el inter es nulo.
     *
     * @access private
     */
    private function lanzarExcepcionSiElAutoloadNoPudoCargarLaClase(mixed $inter, string $nombreDelInter)
    {
        if( is_null($inter) ) {
            throw new InterInexistente($nombreDelInter);
        }
    }

    /**
     * Verifica si la instancia del inter implementa la interfaz Ejecutable
     *
     * @param object $inter
     * @param string $nombreCompletoDelInter
     *
     * @throws InterInvalido si el inter no implementa la interfaz.
     *
     * @access private
     */
    private function lanzarExcepcionSiLaClaseCargadaNoImplementaLaInterfazEjecutable(object $inter, string $nombreCompletoDelInter)
    {
        if( !$inter instanceof Ejecutable ) {
            throw new InterInvalido($nombreCompletoDelInter);
        }
    }

}
