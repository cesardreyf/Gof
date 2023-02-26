<?php

namespace Gof\Sistema\Reportes;

use Gof\Gestor\Registro\Simple\RegistroSimple;
use Gof\Interfaz\Mensajes\Guardable;
use Gof\Sistema\Reportes\Gestor\Reporte;
use Gof\Sistema\Reportes\Interfaz\Reportero;
use Gof\Sistema\Reportes\Plantilla\Errores as PlantillaDeErrores;
use Gof\Sistema\Reportes\Plantilla\Excepciones as PlantillaDeExcepciones;

/**
 * Sistema de reportes de errores y excepciones
 *
 * Sistema encargado de gestionar errores y excepciones sin atrapar.
 *
 * El sistema ofrece una interfaz para la gestión de guardado de los mensajes, lo que posibilita
 * el hecho de cambiar el gestor por defecto que guarda los mensajes localmente a uno donde
 * se pueda guardar los datos en una base de datos.
 *
 * También ofrece la posibilidad de imprimir los reportes para una etapa de depuración.
 *
 * @package Gof\Sistema\Reportes
 */
class Reportes
{
    /**
     * @var Reportero Gestor de errores
     */
    private $gestorDeErrores;

    /**
     * @var Reportero Gestor de excepciones
     */
    private $gestorDeExcepciones;

    /**
     * Constructor
     *
     * @param Guardable $errores     Gestor de guardado para los errores
     * @param Guardable $excepciones Gestor de guardado para las excepciones
     */
    public function __construct(Guardable $errores, Guardable $excepciones)
    {
        $this->gestorDeErrores = new Reporte(new RegistroSimple($errores), new PlantillaDeErrores());
        $this->gestorDeExcepciones = new Reporte(new RegistroSimple($excepciones), new PlantillaDeExcepciones());
    }

    /**
     * Obtiene o define el gestor de errores
     *
     * @param Reportero|null $gestor Establece el gestor de errores, NULL para obtener el actual
     *
     * @return Reportero Devuelve la instancia del gestor de errores actual
     */
    public function errores(/*?Reportero $gestor = null*/): Reportero
    {
        // return $gestor === null ? $this->gestorDeErrores : $this->gestorDeErrores = $gestor;
        return $this->gestorDeErrores;
    }

    /**
     * Obtiene o define el gestor de excepciones
     *
     * @param Reportero|null $gestor Establece el gestor de excepciones, NULL para obtener el actual
     *
     * @return Reportero Devuelve la instancia del gestor de excepciones actual
     */
    public function excepciones(/*?Reportero $gestor = null*/): Reportero
    {
        // return $gestor === null ? $this->gestorDeExcepciones : $this->gestorDeExcepciones = $gestor;
        return $this->gestorDeExcepciones;
    }

    /**
     * Registra la función interna del gestor de errores para recibir los errores del sistema
     *
     * Se registra la función pública 'reportar' del gestor de errores para recibir los errores producidos por PHP.
     *
     * @return void
     */
    public function guardarErrores()
    {
        register_shutdown_function([$this->gestorDeErrores, 'reportar'], error_get_last());
    }

    /**
     * Registra la función interna del gestor de excepciones para recibir las excepciones sin atrapar
     *
     * Se registra la función pública 'reportar' del gestor de excepciones para recibir las excepciones sin atrapar.
     *
     * @return void
     */
    public function guardarExcepciones()
    {
        set_exception_handler([$this->gestorDeExcepciones, 'reportar']);
    }

}
