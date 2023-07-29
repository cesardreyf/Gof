<?php

namespace Gof\Sistema\MVC\Rut;

use Gof\Interfaz\Lista;

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
     * Lista de observadores por defecto del adaptador de Rut
     *
     * @return Gof\Gestor\Enrutador\Rut\Eventos\Interfaz\Observador[]
     */
    public function lista(): array
    {
        return [
            new Observador\Identificador(),
            new Observador\GestorDeInters(),
        ];
    }

}
