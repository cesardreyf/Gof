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
     * @param mixed ...$argumentosDelConstructorDeLaRutaPadre Eso.
     */
    public function __construct(Gestor $eventos, ...$argumentosDelConstructorDeLaRutaPadre)
    {
        parent::__construct(...$argumentosDelConstructorDeLaRutaPadre);
        $this->eventos = $eventos;

        // Genera el evento
        $this->eventos->generar(
            new Evento(
                $this,
                Al::Agregar
            )
        );

        $this->argumentosObligatorios[] = $this->eventos;
    }

}
