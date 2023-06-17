<?php

namespace Gof\Sistema\Formulario;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Interfaz\Bits\Mascara;
use Gof\Sistema\Formulario\Contratos\Errores as InterfazDelGestorDeErrores;
use Gof\Sistema\Formulario\Gestor\Campos as GestorDeCampos;
use Gof\Sistema\Formulario\Gestor\Errores as GestorDeErrores;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;

/**
 * Sistema para obtener datos de formulario
 *
 * Clase que gestiona la obtención de datos desde formularios web.
 *
 * @package Gof\Sistema\Formulario
 */
class Formulario implements Tipos, Errores, Configuracion
{
    /**
     * @var int Máscara de bits con la configuración por defecto
     */
    public const CONFIGURACION_POR_DEFECTO = 0;

    /**
     * @var array Lista de campos del formulario
     */
    private array $campos = [];

    /**
     * @var GestorDeErrores Gestor encargado de manejar los errores del sistema
     */
    private GestorDeErrores $gestorDeErrores;

    /**
     * @var MascaraDeBits Gestor de máscaras de bits para la configuración
     */
    private MascaraDeBits $configuracion;

    /**
     * @var array Datos del formulario
     */
    private GestorDeCampos $gestorDeCampos;

    /**
     * Constructor
     *
     * @param array Array con los datos desde donde se obtendrán los datos del formulario
     */
    public function __construct(array $datos)
    {
        $this->gestorDeErrores = new GestorDeErrores($this->campos);
        $this->configuracion   = new MascaraDeBits(self::CONFIGURACION_POR_DEFECTO);
        $this->gestorDeCampos  = new GestorDeCampos($this->campos, $datos, $this->gestorDeErrores, $this->configuracion);
    }

    /**
     * Obtiene un elemento de tipo Campo con los datos del formulario
     *
     * Crea un elemento de tipo Campo con el valor asociado a la **clave**. Si
     * ocurren errores estos son almacenados internamente dentro del objeto
     * devuelto.
     *
     * @param string $clave Nombre del campo a obtener desde el formulario.
     * @param int    $tipo  Tipo de dato a obtener (ver Tipos).
     *
     * @return Campo Devuelve un objeto de tipo Campo.
     *
     * @see Tipos
     */
    public function campo(string $clave, int $tipo = self::TIPO_STRING): Campo
    {
        return $this->gestorDeCampos->crear($clave, $tipo);
    }

    /**
     * Valida si los valores de los campos del formulario son correctos
     *
     * Recorre la lista de campos y valida cada uno de ellos. Si **todos**
     * los campos son válidos devuelve **true**.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function validar(): bool
    {
        return $this->gestorDeCampos->validar();
    }

    public function errores(): InterfazDelGestorDeErrores
    {
        return $this->gestorDeErrores;
    }

    /**
     * Gestor de configuración
     *
     * Máscara de bits para configurar el comportamiento del sistema.
     *
     * @return Mascara Máscara de bits para la configuración interna del sistema.
     */
    public function configuracion(): Mascara
    {
        return $this->configuracion;
    }

}
