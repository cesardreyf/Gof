<?php

namespace Gof\Gestor\Url\Amigable;

use Gof\Contrato\Peticiones\Peticiones;
use Gof\Datos\Lista\Texto\ListaDeTextos as Lista;

/**
 * Gestor de URL Amigable
 *
 * Clase encargada de generar una lista de objetivos para el gestor Enrutador en base a lo
 * solicitado por la URL, específicamente por la petición GET.
 *
 * @package Gof\Gestor\Url\Amigable;
 */
class GestorUrl implements Peticiones
{
    /**
     * Separador por defecto
     *
     * Caracter utilizado para separar los elementos presentes en la query.
     *
     * @var string
     */
    const SEPARADOR_POR_DEFECTO = '/';

    /**
     * Almacena el caracter que se empleará para separar los recursos solicitados en la cadena
     *
     * @var string
     */
    private string $separador;

    /**
     * Lista de recursos solicitados
     *
     * @var array
     */
    private array $lista = [];

    /**
     * Constructor
     *
     * @param string $separador Caracter usado para dividir la solicitud en elementos de un array
     */
    public function __construct(string $separador = self::SEPARADOR_POR_DEFECTO)
    {
        $this->separador = $separador;
    }

    /**
     * Procesa la petición
     *
     * Limpia la cadena, la sanitiza, la divide en partes teniendo en cuenta el separador y
     * genera una lista ordenada de los recursos solicitados (un array).
     *
     * @param string $peticion Cadena a ser procesada.
     *
     * @return bool Devuelve **true** si se procesó correctamente.
     */
    public function procesar(string $peticion): bool
    {
        $urlLimpia = trim($peticion, $this->separador);
        $urlFiltrada = filter_var($urlLimpia, FILTER_SANITIZE_URL);
        $elementos = explode($this->separador, $urlFiltrada, empty($urlFiltrada) ? -1 : PHP_INT_MAX);
        $arraySinElementosVacios = array_filter($elementos);
        $this->definirListaOrdenada($arraySinElementosVacios);
        return true;
    }

    /**
     * Ordena los elementos del array en la lista interna
     *
     * @param array $recursos Lista de recursos a ordenar
     *
     * @access private
     */
    private function definirListaOrdenada(array $recursos)
    {
        $this->lista = [];
        foreach( $recursos as $recurso ) {
            $this->lista[] = $recurso;
        }
    }

    /**
     * Lista de peticiones
     *
     * Devuelve un array con todos los recursos solicitados del último
     * procesamiento.
     *
     * @return array
     */
    public function lista(): array
    {
        return $this->lista;
    }

}
