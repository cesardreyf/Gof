<?php

namespace Gof\Gestor\Enrutador\Nodos\Datos;

/**
 * Nodo raíz
 *
 * Clase que representa un nodo raíz y solo contiene una lista de nodos hijos.
 *
 * @package Gof\Gestor\Enrutador\Nodos\Datos
 */
class NodoRaiz extends Nodo
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('', '');
    }

}
