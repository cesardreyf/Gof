<?php

namespace Gof\Sistema\Formulario;

use Gof\Datos\Bits\Mascara\MascaraDeBits;
use Gof\Interfaz\Bits\Mascara;
use Gof\Sistema\Formulario\Datos\Campo;
use Gof\Sistema\Formulario\Gestor\Asignar\AsignarCampo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;

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
     * @var array Datos del formulario
     */
    private array $datos;

    /**
     * @var array Lista de campos del formulario
     */
    private array $campos;

    /**
     * @var array Caché de errores
     */
    private array $errores;

    /**
     * @var bool Indica si actualizar la caché de errores
     */
    private bool $actualizarCache;

    /**
     * @var MascaraDeBits Gestor de máscaras de bits para la configuración
     */
    private MascaraDeBits $configuracion;

    /**
     * Constructor
     *
     * @param array Array con los datos desde donde se obtendrán los datos del formulario
     */
    public function __construct(array $datos)
    {
        $this->campos = [];
        $this->datos = $datos;

        $this->errores = [];
        $this->actualizarCache = true;

        $this->configuracion = new MascaraDeBits();
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
        if( isset($this->campos[$clave]) ) {
            return $this->campos[$clave];
        }

        $campo = AsignarCampo::segunTipo($clave, $tipo);
        $siElCampo = new ValidarExistencia($campo, $this->datos);

        if( $siElCampo->existe() ) {
            $campo->valor = $this->datos[$clave];
            $campo->validar();
        }

        $this->actualizarCache = true;
        return $this->campos[$clave] = $campo;
    }

    /**
     * Valida si los valores de los campos del formulario son correctos
     *
     * Internamente consulta la lista de errores y si está vacío devuelve **true**.
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario.
     */
    public function validar(): bool
    {
        return empty($this->errores(true));
    }

    /**
     * Obtiene una lista con todos los errores
     *
     * Genera un array con todos los errores ocurridos en los campos
     * almacenados internamente. Estos son asociados a los nombres de sus
     * propios campos.
     *
     * @param bool $limpiar Limpia la caché de errores y la actualiza.
     *
     * @return array Devuelve un array con todos los errores
     */
    public function errores(bool $limpiar = false): array
    {
        if( $this->actualizarCache || $limpiar ) {
            $this->actualizarCache = false;

            $this->errores = array_map(function($campo) {
                return $campo->error()->mensaje();
            }, array_filter($this->campos, function($campo) {
                return $campo->error()->hay();
            }));
        }

        return $this->errores;
    }

    /**
     * Limpia los errores almacenados internamente
     */
    public function limpiarErrores()
    {
        // $this->actualizarCache = true;
        $this->errores = [];

        array_walk($this->campos, function($campo) {
            $campo->error()->limpiar();
        });
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
