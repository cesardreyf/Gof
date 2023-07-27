<?php

namespace Gof\Sistema\MVC\Rut\Observador;

use Gof\Gestor\Enrutador\Rut\Eventos\Al;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Evento;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Sistema\MVC\Rut\Datos\Ruta;

/**
 * Observador para los identificadores de las rutas del sistema MVC
 *
 * Cada vez que se genere un evento al agregar una nueva ruta se asignará a cada
 * ruta un identificador numérico único.
 *
 * @package Gof\Sistema\MVC\Rut\Observador
 */
class Identificador implements Observador
{
    /**
     * Id libre
     *
     * Almacena un identificador numérico libre/disponible.
     *
     * @var int
     */
    private int $idl = 0;

    /**
     * Asigna un identificador a las rutas
     *
     * Al producirse el evento de tipo **Al::Agregar** se asigna un id único a
     * la ruta.
     *
     * @param Evento $evento
     */
    public function evento(Evento $evento)
    {
        if( $evento->tipo() === Al::Agregar ) {
            $this->asignarId($evento->ruta());
        }
    }

    /**
     * Asigna un id libre a la ruta
     *
     * @param Ruta $ruta Instancia de la ruta
     */
    public function asignarId(Ruta $ruta)
    {
        $ruta->asignarIdentificador($this->idl++);
    }

}
