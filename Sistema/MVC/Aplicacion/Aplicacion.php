<?php

namespace Gof\Sistema\MVC\Aplicacion;

use Exception;
use Gof\Gestor\Autoload\Autoload;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Controlador;
use Gof\Sistema\MVC\Aplicacion\Interfaz\Criterio;
use Gof\Sistema\MVC\Datos\Info;

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
     * @var Info Referencia a los datos compartidos del sistema
     */
    private Info $info;

    /**
     * @var string Almacena el espacio de nombre por defecto para instanciar el controlador
     */
    public string $namespaceDelControlador = '';

    /**
     * Constructor
     *
     * @param Info     &$info     Datos compartidos
     * @param Autoload  $autoload Instancia del gestor de autoload
     */
    public function __construct(Info &$info, Autoload $autoload)
    {
        $this->info     =& $info;
        $this->autoload =  $autoload;
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
     * @throws Exception si no se pudo crear el controlador por que no existe.
     * @throws Exception si la instancia del objeto creado no implementa la interfaz Controlador.
     *
     * @see Controlador
     * @see Criterio
     */
    public function ejecutar(): Controlador
    {
        $controlador = $this->autoload->instanciar($this->namespaceDelControlador . $this->info->controlador, ...$this->info->argumentos);

        if( is_null($controlador) ) {
            throw new Exception("No existe ningún controlador o clase llamado: {$this->info->controlador}");
        }

        if( !$controlador instanceof Controlador ) {
            throw new Exception("La clase '{$this->info->controlador}' no implementa la interfaz " . Controlador::class);
        }

        // Le pasa los parámetros al controlador
        $controlador->parametros($this->info->parametros);

        if( !is_null($this->criterio) ) {
            $this->criterio->ejecutar($controlador);
        }

        return $controlador;
    }

}
