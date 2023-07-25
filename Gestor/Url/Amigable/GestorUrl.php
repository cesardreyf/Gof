<?php

namespace Gof\Gestor\Url\Amigable;

use Gof\Datos\Lista\Texto\ListaDeTextos as Lista;

/**
 * Gestor de URL Amigable
 *
 * Clase encargada de generar una lista de objetivos para el gestor Enrutador en base a lo
 * solicitado por la URL, específicamente por la petición GET.
 *
 * @package Gof\Gestor\Url\Amigable;
 */
class GestorUrl
{
    /**
     * Separador por defecto
     *
     * Caracter utilizado para separar los elementos presentes en la query.
     *
     * @var string
     */
    const SEPARADOR_POR_DEFECTO = '/';

    private Lista $lista;

    /**
     * Constructor
     *
     * En base a una petición (string) crea una lista cuyos elementos son partes de la petición
     * dividos por el caracter indicado en el parámetro **$separador**.
     *
     * @param string $peticion Cadena con la petición.
     */
    public function __construct(string $peticion, string $separador = self::SEPARADOR_POR_DEFECTO)
    {
        $urlLimpia = trim($peticion, $separador);
        $urlFiltrada = filter_var($urlLimpia, FILTER_SANITIZE_URL);
        $elementos = explode($separador, $urlFiltrada/*, empty($urlFiltrada) ? -1 : PHP_INT_MAX*/);
        // $arraySinElementosVacios = array_filter($elementos);
        $this->lista = new Lista($elementos);
    }

    /**
     * Lista de peticiones
     *
     * @return Lista Devuelve la lista de peticiones
     */
    public function lista(): Lista
    {
        return $this->lista;
    }

}
