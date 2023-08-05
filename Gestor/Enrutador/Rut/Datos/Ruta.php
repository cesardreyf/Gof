<?php

namespace Gof\Gestor\Enrutador\Rut\Datos;

use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;

/**
 * Ruta con datos para el gestor de rutas
 *
 * @package Gof\Gestor\Enrutador\Rut\Datos
 */
class Ruta implements IRuta
{
    /**
     * Nombre del recurso
     *
     * @var string
     */
    private string $ruta;

    /**
     * Nombre completo de la clase
     *
     * @var string
     */
    private string $clase;

    /**
     * Lista de rutas hijas
     *
     * @var array<int, IRuta>
     */
    private ?array $hijos = null;

    /**
     * Indica si la ruta contempla parámetros o no
     *
     * @var bool
     */
    private bool $parametros = false;

    /**
     * Almacena alias asociadas a la ruta
     *
     * @var ?array
     */
    private ?array $alias = null;

    /**
     * Almacena los datos de la ruta inexistente
     *
     * @var ?IRuta
     */
    private ?IRuta $inexistente = null;

    /**
     * Argumentos extras para las clases hijas
     *
     * Puede que sea solo temporal.
     *
     * @var array
     */
    protected array $argumentosObligatorios = [];

    /**
     * Constructor
     *
     * @param string $recurso Nombre del recurso que apuntará a la clase.
     * @param string $clase   Nombre de la clase a la que apuntará.
     */
    public function __construct(string $recurso = '', string $clase = '')
    {
        $this->clase = $clase;
        $this->ruta = $recurso;
    }

    /**
     * Crea una nueva ruta y lo agrega como ruta hijo
     *
     * @param string $recurso Nombre del recurso
     * @param string $clase   Nombre de la clase
     *
     * @return IRuta Devuelve una instancia de la nueva ruta hija
     */
    public function agregar(string $recurso, string $clase): IRuta
    {
        return $this->hijos[] = $this->nuevaRuta($recurso, $clase);
    }

    /**
     * Obtiene el nombre completo de la clase asociada
     *
     * @return string Devuelve el nombre de la clase
     */
    public function clase(): string
    {
        return $this->clase;
    }

    /**
     * Obtiene el nombre de la ruta
     *
     * @return string
     */
    public function ruta(): string
    {
        return $this->ruta;
    }

    /**
     * Obtiene las rutas hijas
     *
     * @return array
     */
    public function hijos(): ?array
    {
        return $this->hijos;
    }

    /**
     * Indica si el nodo tiene parámetros
     *
     * @return bool Devuelve **true** si tiene parámetros o **false** de lo contrario.
     */
    public function parametros(?bool $tiene = null): bool
    {
        return $this->parametros = $tiene ?? $this->parametros;
    }

    /**
     * Obtiene o agrega un alias asociado a la ruta
     *
     * Si no se recibe nada por parámetro se devolverá la
     * lista de alias o **null** si no existen alias. Caso
     * contrario se agregará un nuevo alias a la lista.
     *
     * @param string ...$alias Uno o varios alias para la ruta.
     *
     * @return ?array
     */
    public function alias(string ...$alias): ?array
    {
        foreach( $alias as $recurso ) {
            $this->alias[] = $recurso;
        }
        return $this->alias;
    }

    public function inexistente(?string $clase = null): ?IRuta
    {
        return is_null($clase) ? $this->inexistente : $this->inexistente = $this->nuevaRuta(clase: $clase);
    }

    /**
     * Obtiene una instancia de una nueva ruta
     *
     * @param string $recurso Nombre del recurso.
     * @param string $clase   Nombre completo de la clase.
     *
     * @return IRuta
     */
    protected function nuevaRuta(string $recurso = '', string $clase = ''): IRuta
    {
        $argumentosDelConstructor   = $this->argumentosObligatorios;
        $argumentosDelConstructor[] = $recurso;
        $argumentosDelConstructor[] = $clase;
        return new static(...$argumentosDelConstructor);
    }

}
