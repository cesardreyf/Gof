<?php

namespace Gof\Gestor\Enrutador\Rut;

use Gof\Contrato\Enrutador\Enrutador as IEnrutador;
use Gof\Gestor\Enrutador\Rut\Datos\Ruta;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;
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
 * @package Gof\Gestor\Enrutador\Rut
 */
class Enrutador implements IEnrutador
{
    /**
     * @var string Nombre de la clase.
     */
    private string $clase = '';

    /**
     * @var array Parámetros.
     */
    private array $resto = [];

    /**
     * @var IRuta Ruta padre.
     */
    private IRuta $rutas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rutas = new Ruta();
    }

    /**
     * Procesa la solicitud
     *
     * Recorre la lista de rutas en búsqueda del recurso solicitado. Si lo
     * encuentra almacena el nombre de la clase y el resto de la solicitud en
     * un array.
     *
     * @param Lista $objetivos Lista de recursos a buscar en el árbol de nodos.
     */
    public function procesar(Lista $solicitud): bool
    {
        $rutaPadre   = $this->rutas;
        $recursos    = $solicitud->lista();
        $rutas       = $rutaPadre->hijos() ?? [];
        $inexistente = $rutaPadre->inexistente();

        while( ($recurso = array_shift($recursos)) !== null ) {
            foreach( $rutas as $ruta ) {
                if( $this->hayCoincidencia($recurso, $ruta) ) {
                    if( !empty($ruta->inexistente()->clase()) ) {
                        $inexistente = $ruta->inexistente();
                    }

                    $this->clase = $ruta->clase();
                    $rutas = $ruta->hijos() ?? [];
                    $rutaPadre = $ruta;
                    continue 2;
                }
            }

            array_unshift($recursos, $recurso);
            if( $rutaPadre->parametros() === true ) {
                break;
            }

            $this->clase = $inexistente->clase();
            break;
        }

        $this->resto = $recursos;
        return true;
    }

    /**
     * Valida que la ruta y el recurso solicitado coincidan
     *
     * @param string $recurso Nombre de la ruta solicitada
     * @param IRuta  $ruta    Ruta a validar
     *
     * @return bool Devuelve **true** si hay coincidencia
     */
    private function hayCoincidencia(string $recurso, IRuta $ruta): bool
    {
        return $recurso === $ruta->ruta() || (is_array($ruta->alias()) && in_array($recurso, $ruta->alias()));
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

    /**
     * Obtiene la ruta padre
     *
     * @return IRuta
     */
    public function rutas(): IRuta
    {
        return $this->rutas;
    }

}
