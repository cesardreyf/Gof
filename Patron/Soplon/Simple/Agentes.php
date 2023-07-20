<?php

namespace Gof\Patron\Soplon\Simple;

use Gof\Patron\Soplon\Simple\Datos\ID;
use Gof\Patron\Soplon\Simple\Interfaz\Agente;
use Gof\Patron\Soplon\Simple\Excepcion\AgenteInexistente;

/**
 * Gestiona el almacenamiento de los agentes del patrón Soplón Simple
 *
 * @package Gof\Patron\Soplon\Simple
 */
class Agentes
{
    /**
     * Lista de agentes
     *
     * @var Agente[]
     */
    private array $agentes = [];

    /**
     * Puntero interno
     *
     * Índice que apunta al último agente agregado a la lista.
     *
     * @var int
     *
     * @access private
     */
    private int $puntero = 0;

    /**
     * Agrega un nuevo agente a la lista
     *
     * @param Agente $agente Instancia del agente.
     *
     * @return ID Devuelve el id asignado al agente.
     */
    public function agregar(Agente $agente): ID
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
     * @param ID     $agenteViejo Id del agente a ser cambiado.
     * @param Agente $agenteNuevo Instancia del agente nuevo.
     *
     * @return bool Devuelve el estado de la operación.
     */
    public function cambiar(ID $agenteViejo, Agente $agenteNuevo): bool
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
     * @return Agente Instancia del agente asociado al ID.
     *
     * @throws AgenteInexistente si el ID no está asociado a ningún agente.
     */
    public function obtener(ID $agente): Agente
    {
        if( !isset($this->agentes[$agente->id()]) ) {
            throw new AgenteInexistente($agente);
        }
        return $this->agentes[$agente->id()];
    }

    /**
     * Obtiene la lista de agentes
     *
     * @return Agente[] Devuelve un array con todos los agentes registrados.
     */
    public function lista(): array
    {
        return $this->agentes;
    }

}
