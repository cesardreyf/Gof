<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

use Gof\Gestor\Enrutador\Rut\Datos\Ruta as RutaNormal;
use Gof\Gestor\Enrutador\Rut\Eventos\Evento;
use Gof\Gestor\Enrutador\Rut\Eventos\Gestor;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;

/**
 * Ruta con gestión de eventos
 *
 * Al agregar una nueva ruta hija se envía un evento a los observadores con una
 * instancia de la ruta creada.
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos
 */
class Ruta extends RutaNormal
{
    /**
     * Instancia del gestor de eventos
     *
     * @var Gestor
     */
    private Gestor $eventos;

    /**
     * Constructor
     *
     * @param Gestor $eventos Intancia del gestor de ventos.
     * @param string $recurso Nombre del recurso que apuntará a la clase.
     * @param string $clase   Nombre de la clase a la que apuntará.
     */
    public function __construct(Gestor $eventos, string $recurso = '', string $clase = '')
    {
        parent::__construct($recurso, $clase);
        $this->eventos = $eventos;

        // Genera el evento
        $this->eventos->generar(
            new Evento(
                $this,
                Al::Agregar
            )
        );
    }

    /**
     * Crea una nueva ruta y lo agrega como ruta hijo
     *
     * @param string $recurso Nombre del recurso
     * @param string $clase   Nombre de la clase
     *
     * @return IRuta Devuelve una instancia de la nueva ruta hija
     */
    public function agregar(string $recurso, string $clase): IRuta
    {
        return $this->hijos[] = new static($this->eventos, $recurso, $clase);
    }

}
