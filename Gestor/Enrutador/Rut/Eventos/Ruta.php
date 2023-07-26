<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

use Gof\Gestor\Enrutador\Rut\Datos\Ruta as RutaNormal;
use Gof\Gestor\Enrutador\Rut\Eventos\Eventos;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;

/**
 * Ruta con gestión de eventos
 *
 * Al crear la instancia envía un aviso al evento recibido por el constructor
 * pasándole la propia instancia de la ruta como informe.
 *
 * @package Gof\Gestor\Enrutador\Rut\Datos
 */
class Ruta extends RutaNormal
{
    /**
     * Instancia del gestor de eventos
     *
     * @var Evento
     */
    private Eventos $eventos;

    /**
     * Constructor
     *
     * @param Eventos $eventos Intancia del gestor de ventos.
     * @param string  $recurso Nombre del recurso que apuntará a la clase.
     * @param string  $clase   Nombre de la clase a la que apuntará.
     */
    public function __construct(Eventos $eventos, string $recurso = '', string $clase = '')
    {
        parent::__construct($recurso, $clase);

        // Envía el aviso de que esta ruta se agregó
        $eventos->avisar($this, Al::Agregar);

        // Almacena la instancia para las rutas hijas
        $this->eventos = $eventos;
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
        return $this->hijos[] = new self($this->eventos, $recurso, $clase);
    }

}
