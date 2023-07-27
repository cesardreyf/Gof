<?php

namespace Gof\Gestor\Enrutador\Rut\Eventos;

use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador;
use Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observadores as IObservadores;

/**
 * Gestor de observadores
 *
 * Gestiona y almacena la lista de observadores de los eventos de Rut.
 *
 * @package Gof\Gestor\Enrutador\Rut\Eventos
 */
class Observadores implements IObservadores
{
    /**
     * Lista de observadores
     *
     * @var Observador[]
     */
    public array $lista = [];

    /**
     * Agrega un nuevo observador
     *
     * Agrega un observador que recibirá las notificaciones de los eventos
     * producidos.
     *
     * @param Observador $observador Instancia del observador.
     */
    public function agregar(Observador $observador)
    {
        $this->lista[] = $observador;
    }

    /**
     * Obtiene la lista de observadores
     *
     * @return Observador[]
     */
    public function lista(): array
    {
        return $this->lista;
    }

}
