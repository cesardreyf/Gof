<?php

namespace Gof\Sistema\MVC\Rutas\Simple;

/**
 * Datos para el gestor de rutas simple
 *
 * Almacena los datos necesarios para el correcto funcionamiento del gestor de
 * rutas simple.
 *
 * @package Gof\Sistema\MVC\Rutas\Simple
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
     * Lista de controladores públicos
     *
     * Lista con los nombres de los controladores disponibles
     *
     * @var array
     */
    public array $paginasDisponibles = [];
}
