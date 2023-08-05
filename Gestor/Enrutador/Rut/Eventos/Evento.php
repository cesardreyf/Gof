<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Evento as IEvento;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;

/**
 * Almacena los datos del evento
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos
 */
class Evento implements IEvento
{
    /**
     * Ruta a enviar a los observadores
     *
     * @var IRuta
     */
    private IRuta $ruta;

    /**
     * Tipo de evento producido
     *
     * @var Al
     */
    private Al $tipo;

    /**
     * Constructor
     *
     * @param IRuta $ruta Instancia de la ruta
     * @param Al    $tipo Tipo de evento producido
     */
    public function __construct(IRuta $ruta, Al $tipo)
    {
        $this->ruta = $ruta;
        $this->tipo = $tipo;
    }

    /**
     * Obtiene el tipo de evento
     *
     * @return Al
     */
    public function tipo(): Al
    {
        return $this->tipo;
    }

    /**
     * Obtiene la ruta que produjo el evento
     *
     * @return IRuta
     */
    public function ruta(): IRuta
    {
        return $this->ruta;
    }

}
