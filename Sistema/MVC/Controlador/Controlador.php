<?php

namespace Gof\Sistema\MVC\Controlador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInexistente;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Controlador\Interfaz\Controlador as IControlador;
use Gof\Sistema\MVC\Controlador\Interfaz\Criterio;
use Gof\Sistema\MVC\Datos\DAP;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Gestor del controlador del sistema MVC
 *
 * Módulo encargado de la creación y ejecución del controlador.
 *
 * Este módulo crea la instancia del controlador, le pasa los parámetros y
 * luego ejecuta el controlador según un criterio (si lo hay).
 *
 * El criterio se delega a un agente externo el cual estaría a cargo de
 * ejecutar los métodos necesarios en el controlador, según lo requiera el
 * criterio.
 *
 * @package Gof\Sistema\MVC\Controlador
 */
class Controlador implements Ejecutable
{
    /**
     * @var Autoload Instancia del gestor de autoload
     */
    private Autoload $autoload;

    /**
     * @var ?Criterio Instancia del criterio a aplicar al controlador
     */
    private ?Criterio $criterio = null;

    /**
     * @var DAP Referencia al DAP del sistema
     */
    private DAP $dap;

    /**
     * @var Procesos Gestor de procesos de la aplicación
     */
    private Procesos $procesos;

    /**
     * @var ?IControlador Almacena la instancia del controlador creado
     */
    private ?IControlador $instancia = null;

    /**
     * @var string Almacena el espacio de nombre por defecto para instanciar el controlador
     */
    public string $namespaceDelControlador = '';

    /**
     * Constructor
     *
     * @param DAP      &$dap      DAP del sistema
     * @param Autoload  $autoload Instancia del gestor de autoload
     * @param Procesos  $procesos Instancia del gestor de procesos de la aplicación
     */
    public function __construct(DAP &$dap, Autoload $autoload, Procesos $procesos)
    {
        $this->dap =& $dap;
        $this->autoload = $autoload;
        $this->procesos = $procesos;
    }

    /**
     * Ejecuta el controlador
     *
     * Crea la instancia del controlador, le pasa los parámetros y ejecuta un
     * criterio en el mismo.
     *
     * Si existe un criterio registrado este recibirá el controlador para
     * ejecutar los métodos que requiera.
     *
     * @throws ControladorInexistente si no se pudo crear el controlador por que no existe.
     * @throws ControladorInvalido si la instancia del objeto creado no implementa la interfaz Controlador.
     *
     * @see IControlador
     * @see Criterio
     */
    public function ejecutar()
    {
        $controlador = $this->autoload->instanciar($this->namespaceDelControlador . $this->dap->controlador, ...$this->dap->argumentos);

        if( is_null($controlador) ) {
            throw new ControladorInexistente($this->dap->controlador);
        }

        if( !$controlador instanceof IControlador ) {
            throw new ControladorInvalido($this->dap->controlador, IControlador::class);
        }

        // Le pasa los parámetros al controlador
        $controlador->parametros($this->dap->parametros);

        if( !is_null($this->criterio) ) {
            $this->criterio->controlador($controlador);

            // Agrega el criterio a la lista de procesos de la aplicación
            $this->procesos->agregar($this->criterio, Prioridad::Media);
        }
    }

    /**
     * Define el criterio con el que se ejecutará el controlador
     *
     * @param Criterio $criterio Instancia del criterio
     */
    public function criterio(Criterio $criterio)
    {
        $this->criterio = $criterio;
    }

    /**
     * Obtiene la instancia del controlador creado
     *
     * @return ?IControlador Devuelve la instancia del controlador o **null** si aún no se creó.
     */
    public function instancia(): ?IControlador
    {
        return $this->instancia;
    }

}
