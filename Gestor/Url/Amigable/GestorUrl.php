<?php

namespace Gof\Gestor\Url\Amigable;

use Gof\Datos\Lista\Texto\ListaDeTextos as Lista;

/**
 *  Gestor de URL Amigable
 *
 *  Gestor encargado de crear una lista de elementos con la petición del usuario basado en la query.
 *
 *  @package Gof\Gestor\Url\Amigable;
 */
class GestorUrl
{
    /**
     *  Separador por defecto
     *
     *  Caracter utilizado para separar los elementos presentes en la query.
     *
     *  @var string
     */
    const SEPARADOR_POR_DEFECTO = '/';

    /**
     *  Constructor
     *
     *  En base a una petición (string) crea una lista cuyos elementos son partes de la petición
     *  dividos por el caracter indicado en el parámetro **$separador**.
     *
     *  @param string $peticion Cadena con la petición.
     */
    public function __construct(string $peticion, string $separador = self::SEPARADOR_POR_DEFECTO)
    {
        $urlLimpia = trim($peticion, $separador);
        $urlFiltrada = filter_var($urlLimpia, FILTER_SANITIZE_URL);
        $elementos = explode($separador, $urlFiltrada, empty($urlFiltrada) ? -1 : PHP_INT_MAX);
        $arraySinElementosVacios = array_filter($elementos);
        $this->lista = new Lista($arraySinElementosVacios);
    }

    /**
     *  Lista de peticiones
     *
     *  @return Lista Devuelve la lista de peticiones
     */
    public function lista(): Lista
    {
        return $this->lista;
    }

}
