<?php

namespace Gof\Sistema\MVC\Rut;

use Gof\Gestor\Enrutador\Rut\EnrutadorConEventos;
use Gof\Gestor\Enrutador\Rut\Eventos\Gestor;
use Gof\Sistema\MVC\Rut\Datos\Ruta;
use Gof\Sistema\Sistema;

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
    public function __construct()
    {
        $gestor = new Gestor();
        $rutaPadre = new Ruta($gestor);
        parent::__construct($gestor, $rutaPadre);

        // Extensiones para las rutas...
        $observadores = $this->eventos()->observadores();
        $observadores->agregar(new Observador\Identificador());
    }

}
