<?php

namespace Gof\Patron\Soplon\Base;

use Gof\Patron\Soplon\Excepcion\AgenteInexistente;

/**
 * Proporciona funcionalidades básicas para la gestión de agentes en una lista
 *
 * @package Gof\Patron\Soplon\Base
 */
trait Lista
{
    /**
     * Lista de agentes
     *
     * @var array
     *
     * @access protected
     */
    protected array $agentes = [];

    /**
     * Puntero interno
     *
     * Índice que apunta al último agente agregado a la lista.
     *
     * @var int
     *
     * @access protected
     */
    protected int $puntero = 0;

    /**
     * Agrega un nuevo agente a la lista
     *
     * @param mixed $agente Instancia del agente.
     *
     * @return ID Devuelve el id asignado al agente.
     */
    public function agregar(mixed $agente): ID
    {
        $this->agentes[$this->puntero] = $agente;
        return new ID($this->puntero++);
    }

    /**
     * Remueve un agente de la lista
     *
     * @param ID $agente Id del agente a ser removido.
     *
     * @return bool Devuelve el estado de la operación.
     */
    public function remover(ID $agente): bool
    {
        if( isset($this->agentes[$agente->id()]) ) {
            unset($this->agentes[$agente->id()]);
            return true;
        }
        return false;
    }

    /**
     * Cambia un agente por otro
     *
     * @param ID    $agenteViejo Id del agente a ser cambiado.
     * @param mixed $agenteNuevo Instancia del agente nuevo.
     *
     * @return bool Devuelve el estado de la operación.
     */
    public function cambiar(ID $agenteViejo, mixed $agenteNuevo): bool
    {
        if( !$this->remover($agenteViejo) ) {
            return false;
        }

        $tmp = $this->puntero;
        $this->puntero = $agenteViejo->id();
        $this->agregar($agenteNuevo);
        $this->puntero = $tmp;
        return true;
    }

    /**
     * Obtiene un agente según su identificador
     *
     * @param ID $agente Id del agente a obtener.
     *
     * @return mixed Instancia del agente asociado al ID.
     *
     * @throws AgenteInexistente si el ID no está asociado a ningún agente.
     */
    public function obtener(ID $agente): mixed
    {
        if( !isset($this->agentes[$agente->id()]) ) {
            throw new AgenteInexistente($agente);
        }
        return $this->agentes[$agente->id()];
    }

    /**
     * Obtiene la lista de agentes
     *
     * @return array
     */
    public function lista(): array
    {
        return $this->agentes;
    }

}
