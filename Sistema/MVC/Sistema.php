<?php

namespace Gof\Sistema\MVC;

use Gof\Gestor\Autoload\Autoload;
use Gof\Gestor\Autoload\Cargador\Archivos;
use Gof\Gestor\Autoload\Filtro\PSR4 as FiltroPSR4;
use Gof\Sistema\MVC\Aplicacion\Aplicacion;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Controlador\Controlador;
use Gof\Sistema\MVC\Datos\DAP;
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
     * @var DAP Datos de Acceso Público
     */
    private DAP $dap;

    /**
     * @var Aplicacion Instancia del gestor de aplicación.
     */
    private Aplicacion $aplicacion;

    /**
     * @var Controlador Instancia del gestor del controlador.
     */
    private Controlador $controlador;

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

        // Datos compartidos
        $this->dap = new DAP();

        // Gestor de rutas
        $this->rutas = new Rutas($this->dap);

        // Gestor de aplicación
        $this->aplicacion = new Aplicacion();

        // Gestor del controlador
        $this->controlador = new Controlador($this->dap, $this->autoload, $this->aplicacion->procesos());

        // Agregando los primeros procesos
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

}
