<?php

namespace Gof\Sistema\Formulario;

use Gof\Interfaz\Bits\Mascara;
use Gof\Sistema\Formulario\Contratos\Errores as IError;
use Gof\Sistema\Formulario\Contratos\Campos as ICampos;
use Gof\Sistema\Formulario\Gestor\Campos as GestorDeCampos;
use Gof\Sistema\Formulario\Gestor\Errores as GestorDeErrores;
use Gof\Sistema\Formulario\Gestor\Sistema;
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
     * @var Sistema Sistema principal
     */
    private Sistema $sistema;

    /**
     * @var GestorDeErrores Gestor encargado de manejar los errores del sistema
     */
    private GestorDeErrores $gestorDeErrores;

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
        $this->sistema = new sistema();
        $this->sistema->datos = $datos;
        $this->sistema->configuracion->definir(self::CONFIGURACION_POR_DEFECTO);

        $this->gestorDeCampos  = new GestorDeCampos($this->sistema);
        $this->gestorDeErrores = new GestorDeErrores($this->sistema);
    }

    /**
     * Gestor de campos
     *
     * El gestor de campos se encarga de crear y validar los campos.
     *
     * @return ICampos Devuelve una instancia del gestor de campos.
     */
    public function campos(): ICampos
    {
        return $this->gestorDeCampos;
    }

    /**
     * Gestor de errores
     *
     * El gestor de errores se encarga de almacenar y mostrar los mensajes de
     * errores producidos por los campos durante la etapa de validación.
     *
     * @return IError Devuelve una instancia del gestor de errores.
     */
    public function errores(): IError
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
        return $this->sistema->configuracion;
    }

}
