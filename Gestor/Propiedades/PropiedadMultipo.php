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
class PropiedadMultipo extends ListaDeDatos
{
    /**
     * @var string Mensaje de excepción lanzada cuando el tipo de propiedad no es la esperada.
     */
    const EXCEPCION_DE_TIPO = 'La propiedad no implementa las siguientes interfaces: ';

    /**
     * @var int Directiva para ignorar las propiedades que no cumplan con el tipo esperado.
     */
    const IGNORAR_PROPIEDADES_INVALIDAS = 1;

    /**
     * @var string[] Lista de interfaces.
     */
    private array $interfaces;

    /**
     * @var MascaraDeBits Configuración interna del gestor.
     */
    private MascaraDeBits $configuracion;

    /**
     * Constructor
     *
     * @param string $interfaz      Nombre completo del tipo que deberán implementar los elementos.
     * @param string ...$interfaces Más interfaces.
     */
    public function __construct(string $interfaz, string ...$interfaces)
    {
        $this->interfaces = $interfaces;
        $this->interfaces[] = $interfaz;

        foreach( $interfaces as $interfaz ) {
            if( interface_exists($interfaz) === false ) {
                throw new InvalidArgumentException("La interfaz: $interfaz; no existe");
            }
        }

        parent::__construct([]);
        $this->configuracion = new MascaraDeBits(0);
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
        if( $this->implementaLasInterfaces($propiedad) ) {
            return parent::agregar($propiedad, $identificador);
        }

        if( $this->lanzarExcepcion() === false ) {
            return null;
        }
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
        if( $this->implementaLasInterfaces($propiedad) ) {
            return parent::cambiar($identificador, $propiedad);
        }

        return $this->lanzarExcepcion();
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

    /**
     * Valida si la propiedad implementa todas las interfaces registradas
     *
     * @param mixed $propiedad Objeto a validar.
     *
     * @return bool Devuelve **true** si es válido o **false** si el objeto no implementa uno o más interfaces.
     *
     * @access protected
     */
    protected function implementaLasInterfaces(mixed $propiedad): bool
    {
        foreach( $this->interfaces as $interfaz ) {
            if( !$propiedad instanceof $interfaz ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Lanza una excepcion si ocurrió un error de validación
     *
     * @return bool Devuelve **false** si está activado **IGNORAR_PROPIEDADES_INVALIDAS**.
     *
     * @throws InvalidArgumentException si no está activado **IGNORAR_PROPIEDADES_INVALIDAS**.
     *
     * @access protected
     */
    protected function lanzarExcepcion(): bool
    {
        if( $this->configuracion->activados(self::IGNORAR_PROPIEDADES_INVALIDAS) ) {
            return false;
        }

        throw new InvalidArgumentException(self::EXCEPCION_DE_TIPO . implode(', ', $this->interfaces) . '.');
    }

}
