<?php

namespace Gof\Gestor\Propiedades;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Datos\Lista\Datos\ListaDeDatos;
use InvalidArgumentException;

/**
 * Gestor de propiedades
 *
 * Gestiona una lista de propiedades de tipo predeterminado. La clase garantiza que la lista
 * contenga un conjunto de elementos que implementen una interfaz específica.
 *
 * La clase como tal es una extensión de **Gof\Datos\Lista\Datos\ListaDeDatos** al cual se
 * le agrega la tarea de garantizar que los elementos que conforman el conjunto de datos
 * pertenezcan al tipo de datos esperado.
 *
 * @see ListaDeDatos para ver las funcionalidades de la cual extiende esta clase.
 *
 * @package Gof\Gestor\Propiedades
 */
class Propiedad extends ListaDeDatos
{
    /**
     * @var string Mensaje de excepción lanzada cuando el tipo de propiedad no es la esperada.
     */
    const EXCEPCION_DE_TIPO = 'Se esperaba una propiedad de tipo: ';

    /**
     * @var int Directiva para ignorar las propiedades que no cumplan con el tipo esperado.
     */
    const IGNORAR_PROPIEDADES_INVALIDAS = 1;

    /**
     * @var string Nombre de la interfaz que deben implementar los elementos.
     */
    private string $tipo;

    /**
     * Constructor
     *
     * @param string $interfaz      Nombre completo del tipo que deberán implementar los elementos.
     * @param array  $datos         Lista de propiedades (opcional).
     * @param int    $configuracion Máscara de bits con la configuración.
     */
    public function __construct(string $interfaz, array $datos = [], int $configuracion = 0)
    {
        if( interface_exists($interfaz) === false ) {
            throw new InvalidArgumentException("La interfaz: $interfaz; no existe");
        }

        parent::__construct([]);
        $this->tipo = $interfaz;
        $this->configuracion = new MascaraDeBits($configuracion);

        foreach( $datos as $identificador => $propiedad ) {
            $this->agregar($propiedad, $identificador);
        }
    }

    /**
     * Agrega una propiedad a la lista de propiedades
     *
     * Agrega la propiedad si esta implementa la interfaz predefinida, caso contrario se
     * lanzará una excepción. Si en configuración está activado **IGNORAR_PROPIEDADES_INVALIDAS**
     * la función devolverá **null**.
     *
     * Para más información sobre esta función ver **ListaDeDatos**.
     *
     * @param mixed   $propiedad     Propiedad que implemente la interfaz previamente definida.
     * @param ?string $identificador Identificador que tendrá la propiedad en la lista interna.
     *
     * @return ?string Devuelve **null** en caso de error o una cadena con el identificador.
     *
     * @see ListaDeDatos::agregar() para ver la funcionalidad de la cual hereda la clase.
     *
     * @throws InvalidArgumentException si la propiedad no es del tipo esperado.
     */
    public function agregar(mixed $propiedad, ?string $identificador = null): ?string
    {
        if( $propiedad instanceof $this->tipo ) {
            return parent::agregar($propiedad, $identificador);
        }

        if( $this->configuracion->activados(self::IGNORAR_PROPIEDADES_INVALIDAS) ) {
            return null;
        }

        throw new InvalidArgumentException(self::EXCEPCION_DE_TIPO . $this->tipo);
    }

    /**
     * Cambia la propiedad de un elemento interno de la lista de propiedades
     *
     * Cambia una propiedad por otra nueva, siempre y cuando esta implemente la interfaz
     * predefinida, caso contrario se lanzará una excepción. Si en configuración está activado
     * **IGNORAR_PROPIEDADES_INVALIDAS** la función devolverá **false**.
     *
     * Para más información sobre esta función ver **ListaDeDatos**.
     *
     * @param string  $identificador Identificador de la propiedad a modificar.
     * @param mixed   $propiedad     Nueva propiedad.
     *
     * @return ?string Devuelve **true** en caso de éxito o **false** de lo contrario.
     *
     * @see ListaDeDatos::cambiar() para ver la funcionalidad de la cual hereda la clase.
     *
     * @throws InvalidArgumentException si la propiedad no es del tipo esperado.
     */
    public function cambiar(string $identificador, mixed $propiedad): bool
    {
        if( $propiedad instanceof $this->tipo ) {
            return parent::cambiar($identificador, $propiedad);
        }

        if( $this->configuracion->activados(self::IGNORAR_PROPIEDADES_INVALIDAS) ) {
            return false;
        }

        throw new InvalidArgumentException(self::EXCEPCION_DE_TIPO . $this->tipo);
    }

    /**
     * Módulo de configuración del gestor
     *
     * @return MascaraDeBits Devuelve el módulo para configurar el gestor.
     */
    public function configuracion(): MascaraDeBits
    {
        return $this->configuracion;
    }

}
