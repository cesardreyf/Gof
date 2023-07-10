<?php

namespace Gof\Sistema\MVC\Rutas\Nodos;

use Exception;
use Gof\Contrato\Enrutador\Enrutador;
use Gof\Gestor\Enrutador\Nodos\Enrutador as EnrutadorNodos;
use Gof\Gestor\Url\Amigable\GestorUrl;

/**
 * Gestiona la instanciación de un gestor de rutas por nodos
 *
 * @package Gof\Sistema\MVC\Rutas\Nodos
 */
class Gestor
{
    /**
     * @var ?Enrutador Referencia al enrutador
     */
    private ?Enrutador $enrutador;

    /**
     * @var Datos Datos de configuración del enrutador simple
     */
    public Datos $datos;

    /**
     * @var bool Indica si se debe lanzar excepciones al ocurrir un error
     */
    public bool $lanzarExcepcion = false;

    /**
     * Constructor
     *
     * @param ?Enrutador &$enrutador Referencia al enrutador
     */
    public function __construct(?Enrutador &$enrutador)
    {
        $this->enrutador =& $enrutador;
        $this->datos = new Datos();
    }

    /**
     * Procesa la consulta con el gestor de rutas simple
     *
     * Establece EnrutadorSimple como el enrutador principal y procesa la consulta.
     *
     * @return bool Devuelve **true** si no hubo fallos o **false** de lo contrario.
     */
    public function activar(): bool
    {
        if( $this->datosInvalidos() ) {
            return false;
        }

        // Obtiene la consulta desde GET
        $consulta = $_GET[$this->datos->claveGet] ?? '';

        // Procesa la consulta, la divide y la convierte en una lista
        $peticion = new GestorUrl($consulta, $this->datos->separador);

        $this->enrutador = new EnrutadorNodos(
            $peticion->lista(),
            $this->datos->paginasDisponibles,
            $this->datos->paginaPrincipal,
            $this->datos->paginaError404
        );

        return true;
    }

    /**
     * Comprueba la validez de los datos
     *
     * @return bool Devuelve **true** si los datos importantes están vacíos
     *
     * @throws Exception si está activo lanzarExcepcion y los datos son inválidos.
     */
    public function datosInvalidos(): bool
    {
        $datosSonInvalidos =
           empty($this->datos->claveGet) 
        || empty($this->datos->separador)
        || empty($this->datos->paginaPrincipal)
        || empty($this->datos->paginaError404);

        if( $datosSonInvalidos && $this->lanzarExcepcion ) {
            throw new Exception('Los datos necesarios para el correcto funcionamiento del enrutador por nodos son inválidos o no cumplen con lo requerido');
        }

        return $datosSonInvalidos;
    }

}
