<?php

namespace Gof\Sistema\MVC\Rut;

use Gof\Interfaz\Lista;
use Gof\Sistema\MVC\Sistema;

/**
 * Rut adaptado al sistema MVC
 *
 * Adapta el enrutador Rut para complementar al sistema MVC.
 *
 * @package Gof\Sistema\MVC\Rut
 */
class ListaDeObservadores implements Lista
{

    /**
     * Constructor
     *
     * @param Sistema $sistema Instancia del sistema MVC.
     */
    public function __construct(private Sistema $sistema)
    {
    }

    /**
     * Lista de observadores por defecto del adaptador de Rut
     *
     * @return Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador[]
     */
    public function lista(): array
    {
        return [
            new Observador\Identificador(),
            new Observador\GestorDeInters($this->sistema->inters(), $this->sistema->autoload()),
        ];
    }

}
