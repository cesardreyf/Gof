<?php

namespace Gof\Gestor\Enrutador\Rut;

use Gof\Contrato\Enrutador\Enrutador as IEnrutador;
use Gof\Gestor\Enrutador\Rut\Datos\Ruta;
use Gof\Gestor\Enrutador\Rut\Interfaz\Ruta as IRuta;
use Gof\Gestor\Enrutador\Rut\Procesador\Procesador;
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
     * Almacena la ruta con la cual hubo coincidencia
     *
     * @var ?IRuta
     */
    private ?IRuta $rutaFinal = null;

    /**
     * Constructor
     *
     * @param ?IRuta $rutaPadre Ruta padre (Opcional).
     */
    public function __construct(?IRuta $rutaPadre = null)
    {
        $this->rutas = $rutaPadre ?? new Ruta();
    }

    /**
     * Procesa la solicitud
     *
     * Recorre la lista de rutas en búsqueda del recurso solicitado. Si lo
     * encuentra almacena el nombre de la clase y el resto de la solicitud en
     * un array.
     *
     * @param Lista $objetivos Lista de recursos a buscar en el árbol de nodos.
     *
     * @return boo Devuelve el estado de la operación.
     */
    public function procesar(Lista $solicitud): bool
    {
        $seguroDeVida = 999; //< Por las dudas; Temporal...
        $procesador = new Procesador($solicitud, $this->rutas);

        while( $procesador->hayRecursos() && --$seguroDeVida ) {
            if( $procesador->hayCoincidencia() === false ) {
                $procesador->establecerRutaInexistente();
            }
        }

        $rutaFinal = $procesador->obtenerRuta();
        if( empty($rutaFinal->clase()) ) {
            $procesador->establecerRutaInexistente();
            $rutaFinal = $procesador->obtenerRuta();
        }

        $this->definirRutaFinal($rutaFinal);
        if( !is_null($rutaFinal) ) {
            $this->clase = $this->rutaFinal->clase();
            $this->resto = $procesador->recursos();
        }

        return true;
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

    /**
     * Define la ruta final
     *
     * @param ?IRuta $rutaFinal Instancia de la ruta o **null** si no hubo coincidencia.
     */
    protected function definirRutaFinal(?IRuta $rutaFinal = null)
    {
        $this->rutaFinal = $rutaFinal;
    }

}
