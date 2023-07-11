<?php

namespace Gof\Gestor\Enrutador\Rut\Datos;

/**
 * Ruta raíz
 *
 * Clase que representa una ruta raíz y solo contiene una lista de nodos hijos.
 *
 * @package Gof\Gestor\Enrutador\Rut\Datos
 */
class RutaRaiz extends Ruta
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('', '');
    }

}
