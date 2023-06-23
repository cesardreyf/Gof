<?php

namespace Gof\Sistema\Formulario\Gestor\Campos;

use Gof\Sistema\Formulario\Gestor\Sistema;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;

class Validar
{
    private Campo $campo;

    private Sistema $sistema;

    private bool $validez;

    private bool $campoValido;

    public function __construct(Campo $campo, bool &$validez, Sistema $sistema)
    {
        $this->campo = $campo;
        $this->campoValido = true;
        $this->validez =& $validez;
        $this->sistema = $sistema;
    }

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

    public function validarExistencia()
    {
        if( $this->sistema->configuracion->activados(Configuracion::VALIDAR_EXISTENCIA_SIEMPRE) ) {
            $siElCampo = new ValidarExistencia($this->campo, $this->sistema->datos);

            if( !$siElCampo->existe() ) {
                $this->campoValido = false;
            }
        }
    }

    public function establecerValor()
    {
        if( $this->sistema->configuracion->activados(Configuracion::DEFINIR_VALORES_AL_VALIDAR) ) {
            $this->campo->valor = $this->sistema->datos[$this->campo->clave()] ?? null;
        }
    }

    public function validarCampo()
    {
        if( $this->campoValido ) {
            if( !$this->campo->validar() ) {
                $error = $this->campo->error()->codigo();

                if( $error === Errores::ERROR_CAMPO_INEXISTENTE || $error === Errores::ERROR_CAMPO_VACIO ) {
                    if( $this->campo->obligatorio() === false ) {
                        if( $this->sistema->configuracion->activados(Configuracion::LIMPIAR_ERRORES_CAMPOS_OPCIONALES) ) {
                            $this->campo->error()->limpiar();
                        }
                        return;
                    }
                }

                $this->campoValido = false;
            }
        }
    }

    public function validarExtras()
    {
        if( $this->campoValido ) {
            foreach( $this->campo->vextra() as $validacionesExtra ) {
                if( !$validacionesExtra->validar() ) {
                    $this->campoValido = false;
                    break;
                }
            }
        }
    }

}
