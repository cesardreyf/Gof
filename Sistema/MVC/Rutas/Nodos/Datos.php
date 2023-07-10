<?php

namespace Gof\Sistema\MVC\Rutas\Nodos;

use Gof\Gestor\Enrutador\Rut\Datos\NodoRaiz;
use Gof\Gestor\Enrutador\Rut\Interfaz\Nodo;

/**
 * Datos para el gestor de rutas por nodos
 *
 * Almacena los datos necesarios para el correcto funcionamiento del gestor de
 * rutas por nodos.
 *
 * @package Gof\Sistema\MVC\Rutas\Nodos
 */
class Datos
{
    /**
     * Clave a ser usada con la variable super global: $_GET
     *
     * Clave que será usada para obtener la consulta.
     *
     * @var string
     */
    public string $claveGet = '';

    /**
     * Nombre del controlador principal
     *
     * @var string
     */
    public string $paginaPrincipal = '';

    /**
     * Nombre del controlador para los errores 404
     *
     * @var string
     */
    public string $paginaError404 = '';

    /**
     * Caracter que será tenido en cuenta como separador en la consulta
     *
     * @var string
     */
    public string $separador = '';

    /**
     * Conjunto de nodos que representan los controladores accesibles
     *
     * @var Nodo Nodo raíz.
     */
    public Nodo $paginasDisponibles;

    public function __construct()
    {
        $this->paginasDisponibles = new NodoRaiz();
    }

}
