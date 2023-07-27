<?php

namespace Gof\Gestor\Enrutador\Rut;

use Gof\Gestor\Enrutador\Rut\Eventos\Al;
use Gof\Gestor\Enrutador\Rut\Eventos\Evento;
use Gof\Gestor\Enrutador\Rut\Eventos\Gestor as GestorDeEventos;
use Gof\Gestor\Enrutador\Rut\Eventos\Ruta;
use Gof\Gestor\Enrutador\Rut\Excepcion\RutaSinSeguimiento;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;
use Gof\Interfaz\Lista\Textos as Lista;

/**
 * Enrutador con gestión de eventos
 *
 * Este enrutador extiende del enrutador normal de Rut y proporciona una
 * gestión de eventos.
 *
 * Los eventos que se pueden registrar son: al crear una nueva ruta y al
 * procesar la solicitud y al encontrar la ruta solicitada.
 *
 * Al crear una nueva ruta hija se enviará a todos los agentes registrados la
 * instancia de la ruta. De igual modo cuando se procese la solicitud.  Si hay
 * coincidencia se enviará la ruta final a los agentes registrados.
 *
 * @package Gof\Gestor\Enrutador\Rut
 */
class EnrutadorConEventos extends Enrutador
{
    /**
     * Gestor de eventos
     *
     * @var Eventos
     */
    private GestorDeEventos $eventos;

    /**
     * Constructor
     *
     * @param ?GestorDeEventos $gEventos  Instancia del gestor de eventos (Opcional).
     * @param ?IRuta           $rutaPadre Instancia de la ruta padre (Opcional).
     *
     * @throws RutaSinSeguimiento si se pasa una ruta padre y no se pasa el gestor de eventos.
     */
    public function __construct(?GestorDeEventos $gEventos = null, ?IRuta $rutaPadre = null)
    {
        if( is_null($gEventos) && !is_null($rutaPadre) ) {
            throw new RutaSinSeguimiento();
        }

        $this->eventos = $gEventos ?? new GestorDeEventos();
        parent::__construct($rutaPadre ?? new Ruta($this->eventos));
    }

    /**
     * Procesa la solicitud
     *
     * Recorre la lista de rutas en búsqueda del recurso solicitado. Si lo
     * encuentra almacena el nombre de la clase y el resto de la solicitud en
     * un array.
     *
     * Si se haya una ruta final, la misma será pasada a todos los agentes que
     * estén registrados en el evento alProcesar.
     *
     * @param Lista $objetivos Lista de recursos a buscar en el árbol de nodos.
     *
     * @return bool Devuelve el estado de la operación.
     */
    public function procesar(Lista $solicitud): bool
    {
        $resultado = parent::procesar($solicitud);
        $this->eventos->generar(
            new Evento(
                $this->rutaFinal,
                Al::Procesar,
            )
        );
        return $resultado;
    }

    /**
     * Obtiene el gestor de eventos
     *
     * @return Eventos
     */
    public function eventos(): GestorDeEventos
    {
        return $this->eventos;
    }

}
