<?php

namespace Gof\Gestor\Enrutador\Nodos;

use Gof\Gestor\Enrutador\Nodos\Datos\Nodo;
use Gof\Interfaz\Enrutador\Enrutador as IEnrutador;
use Gof\Interfaz\Lista\Textos as Lista;

/**
 * Enrutador basado en nodos
 *
 * Clase encargada de gestionar rutas de acceso. En base a una solicitud en
 * forma de lista de recursos y un árbol de nodos, que representan a las
 * páginas accesibles, genera un nombre de clase para un sistema MVC
 * (controlador) y una lista de parámetros con el resto de la solicitud.
 *
 * En caso de que la solicitud esté vacía se devolverá el nombre de la **clase
 * principal** (pedido en el constructor). O si el recurso solicitado no existe
 * dentro del árbol de nodos, osea que no se considera una página accesible, el
 * nombre de la clase será **clase inexistente**.
 *
 * @package Gof\Gestor\Enrutador\Nodos
 */
class Enrutador implements IEnrutador
{
    /**
     * @var string Nombre de la clase.
     */
    private string $clase;

    /**
     * @var array Parámetros.
     */
    private array $resto = [];

    /**
     * Constructor
     *
     * @param Lista  $objetivos   Lista de recursos a buscar en el árbol de nodos.
     * @param Nodo   $nodoPadre   Nodo raíz que contenga las páginas accesibles.
     * @param string $principal   Clase a asociar al recurso principal (en caso de ausencia de recursos).
     * @param string $inexistente Clase a asociar en caso de que el recurso solicitado no coincida con ningún nodo.
     */
    public function __construct(Lista $solicitud, Nodo $nodoPadre, string $principal, string $inexistente)
    {
        $this->clase = $principal;
        $nodos = $nodoPadre->hijos();
        $recursos = $solicitud->lista();

        while( $recurso = array_shift($recursos) ) {
            foreach( $nodos as $nodo ) {
                if( in_array($recurso, $nodo->paginas()) ) {
                    $this->clase = $nodo->clase();
                    $nodos = $nodo->hijos();
                    $nodoPadre = $nodo;
                    continue 2;
                }
            }

            array_unshift($recursos, $recurso);
            if( $nodoPadre->parametros() === true ) {
                break;
            }

            $this->clase = $inexistente;
            break;
        }

        $this->resto = $recursos;
    }

    /**
     * Obtiene el nombre de la clase
     *
     * @return string Devuelve el nombre completo de la clase.
     */
    public function nombreClase(): string
    {
        return $this->clase;
    }

    /**
     * Resto de la lista de objetivos luego de la búsqueda en el árbol de nodos
     *
     * Obtiene un array con el resto de elementos que no hicieron falta para el enrutamiento
     * y obtención de la clase en el árbol de nodos. En URL amigables esto serían los parámetros.
     *
     * @return array Devuelve el sobrante de los objetivos pasados por el constructor.
     */
    public function resto(): array
    {
        return $this->resto;
    }

}
