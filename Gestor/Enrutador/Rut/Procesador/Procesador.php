<?php

namespace Gof\Gestor\Enrutador\Rut\Procesador;

use Gof\Gestor\Enrutador\Rut\Datos\Ruta;
use Gof\Gestor\Enrutador\Rut\Interfaz\Inexistente;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;
use Gof\Interfaz\Lista\Textos as Lista;

/**
 * Submódulo encargado de procesar la búsqueda de la ruta
 *
 * Este módulo se encarga de procesar una lista de recursos en búsqueda
 * de una ruta que coincida con lo solicitado.
 *
 * @package Gof\Gestor\Enrutador\Rut\Procesador
 */
class Procesador
{
    /**
     * Almacena la lista de recursos
     *
     * @var array
     */
    private array $recursos;

    /**
     * Almacena la ruta padre
     *
     * @var IRuta
     */
    private IRuta $rutaPadre;

    /**
     * Almacena el último recurso solicitado
     *
     * @var ?string
     */
    private ?string $recurso = null;

    /**
     * Almacena la ruta final
     *
     * @see Procesador::obtenerRuta()
     *
     * @var ?IRuta
     */
    private ?IRuta $rutaFinal = null;

    /**
     * Almacena los datos para definir la ruta inexistente
     *
     * @var IRuta
     */
    private ?IRuta $inexistente;

    /**
     * Constructor
     *
     * @param Lista $recursos  Lista de recursos solicitados.
     * @param IRuta $rutaPadre Arbol de nodos con las rutas disponibles.
     */
    public function __construct(Lista $recursos, IRuta $rutaPadre)
    {
        $this->rutaPadre = $rutaPadre;
        $this->recursos = $recursos->lista();
        $this->inexistente = $rutaPadre->inexistente();
    }

    /**
     * Verifica si existen recursos por procesar
     *
     * @return bool Devuelve **true** si aún hay recursos o **false** de lo contrario.
     */
    public function hayRecursos(): bool
    {
        return !is_null($this->recurso = array_shift($this->recursos));
    }

    /**
     * Valida que la ruta y el recurso solicitado coincidan
     *
     * @return bool Devuelve **true** si hay coincidencia
     */
    public function hayCoincidencia(): bool
    {
        if( is_null($this->recurso) ) {
            return false;
        }

        $rutas = $this->rutaPadre->hijos() ?? [];
        foreach( $rutas as $ruta ) {
            if( $this->recurso === $ruta->ruta() || (is_array($ruta->alias()) && in_array($this->recurso, $ruta->alias())) ) {
                if( !is_null($ruta->inexistente()) ) {
                    $this->inexistente = $ruta->inexistente();
                }

                $this->rutaFinal = $ruta;
                $this->rutaPadre = $ruta;
                return true;
            }
        }

        // Agrega el recurso nuevamente a la lista
        array_unshift($this->recursos, $this->recurso);

        // Si la ruta padre acepta parámetros hay coincidencia
        if( $this->rutaPadre->parametros() === true ) {
            return true;
        }

        return false;
    }

    /**
     * Establece la ruta final como inexistente
     *
     * @see Procesador::obtenerRuta()
     */
    public function establecerRutaInexistente()
    {
        $this->rutaFinal = $this->inexistente;
    }

    /**
     * Obtiene la lista de recursos
     *
     * Devuelve un array con aquellos recursos que no fueron usados para la
     * búsqueda de la ruta final.
     *
     * @return array
     */
    public function recursos(): array
    {
        return $this->recursos;
    }

    /**
     * Obtiene la ruta final
     *
     * La ruta final es la última ruta que coincide con el recurso solicitado.
     *
     * Si no hubo coincidencia la ruta final será una nueva ruta con la clase
     * definida por la propiedad ruta->inexistente->clase de la ruta padre más
     * cercana, si existe; caso contrario el nombre de la clase estará vacío.
     *
     * @return ?IRuta
     */
    public function obtenerRuta(): ?IRuta
    {
        return $this->rutaFinal;
    }

}
