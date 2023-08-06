<?php

namespace Gof\Sistema\MVC;

use Gof\Gestor\Autoload\Autoload;
use Gof\Gestor\Autoload\Cargador\Archivos;
use Gof\Gestor\Autoload\Filtro\PSR4 as FiltroPSR4;
use Gof\Sistema\MVC\Aplicacion\Aplicacion;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Controlador\Controlador;
use Gof\Sistema\MVC\Inters\Fijos\Todos as IntersFijos;
use Gof\Sistema\MVC\Inters\Inters;
use Gof\Sistema\MVC\Peticiones\Peticiones;
use Gof\Sistema\MVC\Registros\Registros;
use Gof\Sistema\MVC\Rutas\Rutas;

/**
 * Sistema MVC
 *
 * Conjunto de herramientas que ofrecen un servicio capaz de ejecutar y mantener
 * una aplicación web para la arquitectura MVC (Modelo-Vista-Controlador).
 *
 * @package Gof\Sistema\MVC
 */
class Sistema
{
    /**
     * @var Registros Instancia del gestor de registros de errores
     */
    private Registros $registros;

    /**
     * @var Autoload Instancia del gestor de autoload
     */
    private Autoload $autoload;

    /**
     * @var Rutas Instancia del gestor de rutas
     */
    private Rutas $rutas;

    /**
     * @var Aplicacion Instancia del gestor de aplicación.
     */
    private Aplicacion $aplicacion;

    /**
     * @var Controlador Instancia del gestor del controlador.
     */
    private Controlador $controlador;

    /**
     * @var Inters Instancia del gestor de los inters
     */
    private Inters $inters;

    /**
     * @var Peticiones Instancia del gestor de peticiones
     */
    private Peticiones $peticiones;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Gestor de registros
        $this->registros = new Registros();

        // Gestor de autoload
        $cargadorDeArchivos = new Archivos();
        $cargadorDeArchivos->configuracion()->activar(Archivos::INCLUIR_EXTENSION);

        $this->autoload = new Autoload($cargadorDeArchivos, new FiltroPSR4());
        $this->autoload->registrar();

        // Gestor de aplicación
        $this->aplicacion = new Aplicacion();

        // Gestor de peticiones
        $this->peticiones = new Peticiones();

        // Gestor de rutas
        $this->rutas = new Rutas();

        // Gestor de los inters
        $this->inters = new Inters(
            $this->aplicacion->procesos()->agregable(
                Prioridad::Media
            )
        );

        // Agrega los Inters por defecto del sistema
        $this->inters->agregarLista(new IntersFijos());

        // Gestor del controlador
        $this->controlador = new Controlador(
            $this->autoload,
            $this->aplicacion->procesos()->agregable(
                Prioridad::Baja
            )
        );

        // Agregando los primeros procesos
        $this->aplicacion->procesos()->agregar($this->peticiones,  Prioridad::Alta);
        $this->aplicacion->procesos()->agregar($this->rutas,       Prioridad::Alta);
        $this->aplicacion->procesos()->agregar($this->controlador, Prioridad::Alta);
    }

    /**
     * Obtiene el gestor de registros de errores
     *
     * @return Registros
     */
    public function registros(): Registros
    {
        return $this->registros;
    }

    /**
     * Obtiene el gestor de autoload
     *
     * @return Autoload
     */
    public function autoload(): Autoload
    {
        return $this->autoload;
    }

    /**
     * Obtiene el gestor de rutas
     *
     * @return Rutas
     */
    public function rutas(): Rutas
    {
        return $this->rutas;
    }

    /**
     * Obtiene el gestor de aplicación
     *
     * @return Aplicacion
     */
    public function aplicacion(): Aplicacion
    {
        return $this->aplicacion;
    }

    /**
     * Obtiene el gestor de controlador
     *
     * @return Controlador
     */
    public function controlador(): Controlador
    {
        return $this->controlador;
    }

    /**
     * Obtiene el gestor de inters
     *
     * @return Inters
     */
    public function inters(): Inters
    {
        return $this->inters;
    }

    /**
     * Obtiene el gestor de peticiones
     *
     * @return Peticiones
     */
    public function peticiones(): Peticiones
    {
        return $this->peticiones;
    }

}
