<?php

namespace Gof\Gestor\Enrutador\Rut\Datos;

use Gof\Gestor\Enrutador\Rut\Interfaz\Nodo as INodo;

/**
 * Nodo con datos para el gestor de rutas por nodo
 *
 * @package Gof\Gestor\Enrutador\Rut\Datos
 */
class Nodo implements INodo
{
    /**
     * @var array<int, INodo> Lista de nodos hijos.
     */
    private array $nodos;

    /**
     * @var string[] Lista de páginas asociadas a la clase.
     */
    private array $paginas;

    /**
     * @var bool Indica si el nodo contempla parámetros o no.
     */
    private bool $parametros;

    /**
     * @var string Nombre completo de la clase.
     */
    private string $nombreClase;

    /**
     * Constructor
     *
     * @param string $pagina Nombre del recurso (URL) que apuntará a la clase.
     * @param string $clase  Nombre de la clase a la que apuntará.
     */
    public function __construct(string $pagina, string $nombreClase)
    {
        $this->nodos = [];
        $this->alias($pagina);
        $this->parametros = false;
        $this->nombreClase = $nombreClase;
    }

    /**
     * Agrega un nuevo nodo hijo al array interno de nodos hijos
     *
     * @param INodo $nodo Nuevo nodo hijo.
     *
     * @return INodo Devuelve una instancia del mismo nodo recibido por parámetro.
     */
    public function agregar(INodo $nodo): INodo
    {
        return $this->nodos[] = $nodo;
    }

    /**
     * Crea un nuevo alias para la clase
     *
     * Agrega un nuevo recurso que apuntará a la misma clase.
     *
     * @param string $pagina Nombre del recurso que apuntará a la clase.
     */
    public function alias(string $pagina)
    {
        $this->paginas[] = $pagina;
    }

    /**
     * Obtiene el nombre completo de la clase asociada
     *
     * @return string Devuelve el nombre de la clase
     */
    public function clase(): string
    {
        return $this->nombreClase;
    }

    /**
     * Obtiene una lista con los recursos asociados a la clase
     *
     * @return array Devuelve una lista de páginas asociadas a la clase
     */
    public function paginas(): array
    {
        return $this->paginas;
    }

    /**
     * Obtiene los nodos hijos
     *
     * @return array Devuelve el conjunto de nodos hijos
     */
    public function hijos(): array
    {
        return $this->nodos;
    }

    /**
     * Indica si el nodo tiene parámetros
     *
     * @return bool Devuelve **true** si tiene parámetros o **false** de lo contrario.
     */
    public function parametros(?bool $tiene = null): bool
    {
        return $tiene === null ? $this->parametros : $this->parametros = $tiene;
    }

}
