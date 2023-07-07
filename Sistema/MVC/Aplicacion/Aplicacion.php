<?php

namespace Gof\Sistema\MVC\Aplicacion;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\Excepcion\ControladorInexistente;
use Gof\Sistema\MVC\Aplicacion\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Controlador;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Criterio;
use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;
use Gof\Sistema\MVC\Aplicacion\Procesos\Procesos;
use Gof\Sistema\MVC\Datos\DAP;

/**
 * Gestor de Aplicacion
 *
 * Gestor encargado de ejecutar el controlador, pasarle los parámetros y
 * aplicarle un criterio.
 *
 * El criterio se delega a un agente externo el cual estaría a cargo de
 * ejecutar los métodos necesarios en el controlador, según lo requiera el
 * criterio.
 *
 * @package Gof\Sistema\MVC\Aplicacion
 */
class Aplicacion
{
    /**
     * @var ?Criterio Instancia del criterio a aplicar al controlador
     */
    public ?Criterio $criterio = null;

    /**
     * @var Autoload Instancia del gestor de autoload
     */
    private Autoload $autoload;

    /**
     * @var DAP Referencia al DAP del sistema
     */
    private DAP $dap;

    /**
     * @var Procesos Instancia del gestor de procesos
     */
    private Procesos $procesos;

    /**
     * @var string Almacena el espacio de nombre por defecto para instanciar el controlador
     */
    public string $namespaceDelControlador = '';

    /**
     * Constructor
     *
     * @param DAP      &$dap      DAP del sistema
     * @param Autoload  $autoload Instancia del gestor de autoload
     */
    public function __construct(DAP &$dap, Autoload $autoload)
    {
        $this->dap =& $dap;
        $this->autoload = $autoload;
        $this->procesos = new Procesos();
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
     * @return Controlador Devuelve la instancia del controlador creado.
     *
     * @throws ControladorInexistente si no se pudo crear el controlador por que no existe.
     * @throws ControladorInvalido si la instancia del objeto creado no implementa la interfaz Controlador.
     *
     * @see Controlador
     * @see Criterio
     */
    public function ejecutar(): Controlador
    {
        $controlador = $this->autoload->instanciar($this->namespaceDelControlador . $this->dap->controlador, ...$this->dap->argumentos);

        if( is_null($controlador) ) {
            throw new ControladorInexistente($this->dap->controlador);
        }

        if( !$controlador instanceof Controlador ) {
            throw new ControladorInvalido($this->dap->controlador, Controlador::class);
        }

        // Le pasa los parámetros al controlador
        $controlador->parametros($this->dap->parametros);

        if( !is_null($this->criterio) ) {
            $this->criterio->controlador($controlador);
            $this->procesos->agregar($this->criterio, Prioridad::Media);
        }

        $this->procesos->ejecutar();
        return $controlador;
    }

    /**
     * Obtiene el gestor de procesos
     *
     * @return Procesos
     */
    public function procesos(): Procesos
    {
        return $this->procesos;
    }

}
