<?php

namespace Gof\Sistema\MVC\Controlador;

use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\DAP\DAP;
use Gof\Sistema\MVC\Aplicacion\DAP\N1 as DAPN1;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorIndefinido;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInexistente;
use Gof\Sistema\MVC\Controlador\Excepcion\ControladorInvalido;
use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Ejecuta el controlador
 *
 * Submódulo encargado de crear la instancia y ejecutar el controlador.
 *
 * @package Gof\Sistema\MVC\Controlador
 */
class Ejecutor implements Ejecutable
{
    /**
     * Almacena una instancia del gestor de autoload
     *
     * @var Autoload
     */
    private Autoload $autoload;

    /**
     * Referencia al D.A.P de nivel 1
     *
     * @var DAPN1
     */
    private DAPN1 $dapn1;

    /**
     * Constructor
     *
     * @param Autoload $autoload Instancia del gestor de autoload
     */
    public function __construct(Autoload $autoload, DAPN1 $dapn1)
    {
        $this->autoload = $autoload;
        $this->dapn1 = $dapn1;
    }

    /**
     * Crea la instancia del controlador y lo ejecuta
     *
     * Crea una instancia del controlador teniendo en cuenta los valores
     * actuales almacenados en el DAP de nivel 1, como el nombre del
     * controlador y los argumentos. Este último son los argumentos que serán
     * pasados al constructor del controlador.
     *
     * @param DAP $dap Datos de acceso público.
     *
     * @throws ControladorIndefinido si el registro que guarda el nombre del controlador está vacío.
     * @throws ControladorInexistente si el autoload no pudo crear la instancia del controlador.
     * @throws ControladorInvalido si el controlador no implementa la interfaz Ejecutable.
     *
     * @see Ejecutable
     */
    public function ejecutar(DAP $dap)
    {
        if( empty($this->dapn1->controlador) ) {
            throw new ControladorIndefinido();
        }

        $controlador = $this->autoload->instanciar($this->dapn1->controlador, ...$this->dapn1->argumentos);
        if( is_null($controlador) ) {
            throw new ControladorInexistente($this->dapn1->controlador);
        }

        if( !$controlador instanceof Ejecutable ) {
            throw new ControladorInvalido($this->dapn1->controlador, Ejecutable::class);
        }

        $controlador->ejecutar($dap);
    }

}
