<?php

namespace Gof\Patron\Soplon\Simple;

use Gof\Patron\Soplon\Base\ID;
use Gof\Patron\Soplon\Simple\Interfaz\Agente;
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

    public function agregar(Agente $agente): ID
    {
        return $this->agregarAgente($agente);
    }

    public function cambiar(ID $agenteViejo, Agente $agenteNuevo): bool
    {
        return $this->cambiarAgente($agenteViejo, $agenteNuevo);
    }

    public function obtener(ID $agente): Agente
    {
        return $this->obtenerAgente($agente);
    }

}
