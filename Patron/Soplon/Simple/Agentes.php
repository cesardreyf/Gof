<?php

namespace Gof\Patron\Soplon\Simple;

use Gof\Patron\Soplon\Base\ID;
use Gof\Patron\Soplon\Base\Lista;

/**
 * Gestiona el almacenamiento de los agentes del patrón Soplón Simple
 *
 * @package Gof\Patron\Soplon\Simple
 */
class Agentes
{
    use Lista {
        Lista::agregar as private agregarAgente;
        Lista::cambiar as private cambiarAgente;
        Lista::obtener as private obtenerAgente;
    }

    /**
     * Agrega un agente a la lista
     *
     * @param Agente $agente Instancia del agente.
     *
     * @return ID
     */
    public function agregar(Agente $agente): ID
    {
        return $this->agregarAgente($agente);
    }

    /**
     * Cambia un agente registrado por otro nuevo
     *
     * @param ID     $agenteViejo Identificador del agente a ser cambiado.
     * @param Agente $agenteNuevo Instancia del nuevo agente que reemplazará al viejo.
     *
     * @return bool Devuelve el estado de la operación.
     */
    public function cambiar(ID $agenteViejo, Agente $agenteNuevo): bool
    {
        return $this->cambiarAgente($agenteViejo, $agenteNuevo);
    }

    /**
     * Obtiene un agente según su id
     *
     * @param ID $agente Identificador del agente a obtener.
     *
     * @return Agente Devuelve la instancia del agente.
     */
    public function obtener(ID $agente): Agente
    {
        return $this->obtenerAgente($agente);
    }

}
