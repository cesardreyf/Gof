<?php

namespace Gof\Sistema\MVC\Rut;

use Gof\Gestor\Enrutador\Rut\EnrutadorConEventos;
use Gof\Gestor\Enrutador\Rut\Eventos\Gestor;
use Gof\Sistema\MVC\Rut\Datos\Ruta;
use Gof\Sistema\MVC\Sistema;

/**
 * Rut adaptado al sistema MVC
 *
 * Adapta el enrutador Rut para complementar al sistema MVC.
 *
 * @package Gof\Sistema\MVC\Rut
 */
class Rut extends EnrutadorConEventos
{

    /**
     * Constructor
     */
    public function __construct(Sistema $sistema)
    {
        $gestor = new Gestor();
        $rutaPadre = new Ruta($gestor);
        parent::__construct($gestor, $rutaPadre);

        $observadores = $this->eventos()->observadores();
        $listaDeObservadores = new ListaDeObservadores($sistema);

        foreach( $listaDeObservadores->lista() as $observador ) {
            $observadores->agregar($observador);
        }
    }

}
