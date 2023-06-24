<?php

namespace Gof\Sistema\Formulario\Gestor\Campos;

use Gof\Sistema\Formulario\Gestor\Sistema;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;

/**
 * Módulo encargado de validar un campo
 *
 * @package Gof\Sistema\Formulario\Gestor\Campos
 */
class Validar
{
    /**
     * @var Campo Instancia del campo a validar
     */
    private Campo $campo;

    /**
     * @var Sistema Instancia del sistema
     */
    private Sistema $sistema;

    /**
     * Referencia a un valor bool externo
     *
     * Variable externa donde establecer **false** cuando uno o más campos sea inválido.
     *
     * @var bool
     */
    private bool $validez;

    /**
     * @var bool Indica si el campo es válido
     */
    private bool $campoValido;

    /**
     * Constructor
     *
     * @param Campo   $campo   Instancia del campo a validar.
     * @param bool   &$validez Referencia a un bool que se establecerá a **false** si un campo es inválido.
     * @param Sistema $sistema Instancia del sisma.
     */
    public function __construct(Campo $campo, bool &$validez, Sistema $sistema)
    {
        $this->campo = $campo;
        $this->campoValido = true;
        $this->validez =& $validez;
        $this->sistema = $sistema;

        $this->validarExistencia();
        $this->establecerValor();
        $this->validarCampo();
        $this->validarExtras();
    }

    /**
     * Destructor
     *
     * Si el campo es inválido establece el estado **validez** externo a **false**.
     * Si está activo la directiva de configuración LIMPIAR_ERRORES_DE_CAMPOS_VALIDOS
     * limpia el campo cualquier error almacenado en el campo si el campo es válido.
     */
    public function __destruct()
    {
        if( !$this->campoValido ) {
            $this->validez = false;
            return;
        }

        if( $this->sistema->configuracion->activados(Configuracion::LIMPIAR_ERRORES_DE_CAMPOS_VALIDOS) ) {
            $this->campo->error()->limpiar();
        }
    }

    /**
     * Verifica si el campo existe en los datos del formulario
     */
    public function validarExistencia()
    {
        if( $this->sistema->configuracion->activados(Configuracion::VALIDAR_EXISTENCIA_SIEMPRE) ) {
            $siElCampo = new ValidarExistencia($this->campo, $this->sistema->datos);

            if( !$siElCampo->existe() ) {
                $this->campoValido = false;
            }
        }
    }

    /**
     * Establece el valor del campo
     *
     * Define el valor del campo con el almacenado en los datos del formulario.
     */
    public function establecerValor()
    {
        if( $this->sistema->configuracion->activados(Configuracion::DEFINIR_VALORES_AL_VALIDAR) ) {
            $this->campo->valor = $this->sistema->datos[$this->campo->clave()] ?? null;
        }
    }

    /**
     * Valida el campo
     *
     * @see ValidarCampo
     */
    public function validarCampo()
    {
        if( $this->campoValido ) {
            static $validacionDelCampo = new ValidarCampo();
            $validacionDelCampo->limpiarErroresDeCampoOpcional(
                $this->sistema->configuracion->activados(Configuracion::LIMPIAR_ERRORES_CAMPOS_OPCIONALES)
            );

            if( $validacionDelCampo->validar($this->campo) === false ) {
                $this->campoValido = false;
            }
        }
    }

    /**
     * Invoca el método 'validar' de las validaciones extras del campo
     *
     * @see ValidarExtras::validar()
     */
    public function validarExtras()
    {
        if( $this->campoValido && !ValidarExtras::validar($this->campo) ) {
            $this->campoValido = false;
        }
    }

}
