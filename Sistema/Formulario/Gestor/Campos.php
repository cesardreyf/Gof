<?php

namespace Gof\Sistema\Formulario\Gestor;

use Gof\Interfaz\Bits\Mascara;
use Gof\Interfaz\Lista;
use Gof\Sistema\Formulario\Contratos\Campos as ICampos;
use Gof\Sistema\Formulario\Gestor\Asignar\AsignarCampo;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Interfaz\Errores;
use Gof\Sistema\Formulario\Interfaz\Tipos;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;

/**
 * Gestor de campos
 *
 * Clase encargada de gestionar la validación de los campos.
 *
 * @package Gof\Sistema\Formulario\Gestor
 */
class Campos implements ICampos
{
    /**
     * @var Sistema
     */
    private Sistema $sistema;

    /**
     * Constructor
     *
     * @param Sistema $sistema       Instancia del sistema.
     */
    public function __construct(Sistema $sistema)
    {
        $this->sistema = $sistema;
    }

    /**
     * Crea un nuevo campo según el tipo
     *
     * Agrega a la lista de campos un nuevo campo según el tipo indicado. Si el
     * nombre del campo ya se encuentra registrado devuelve la instancia del mismo.
     *
     * @param string $nombreDelCampo Nombre del campo.
     * @param int    $tipoDeDato     Tipo de dato del campo.
     *
     * @return Campo Devuelve una instancia del campo.
     */
    public function crear(string $nombreDelCampo, int $tipoDeDato = Tipos::TIPO_STRING): Campo
    {
        if( isset($this->sistema->campos[$nombreDelCampo]) ) {
            return $this->sistema->campos[$nombreDelCampo];
        }

        $campo     = AsignarCampo::segunTipo($nombreDelCampo, $tipoDeDato);
        $siElCampo = new ValidarExistencia($campo, $this->sistema->datos);

        if( $siElCampo->existe() ) {
            $campo->valor = $this->sistema->datos[$nombreDelCampo];

            if( $this->sistema->configuracion->activados(Configuracion::VALIDAR_AL_CREAR) ) {
                $campo->validar();
            }
        }

        $this->sistema->actualizarCache = true;
        return $this->sistema->campos[$nombreDelCampo] = $campo;
    }

    /**
     * Obtiene un campo según su nombre
     *
     * @return ?Campo Devuelve una instancia del campo requerido o **null** si no existe.
     */
    public function obtener(string $nombreDelCampo): ?Campo
    {
        return $this->sistema->campos[$nombreDelCampo] ?? null;
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
        $todosLosCamposSonValidos = true;

        array_walk($this->sistema->campos, function(Campo $campo) use (&$todosLosCamposSonValidos) {
            if( !$campo->validar() ) {
                $error = $campo->error()->codigo();

                if( $error === Errores::ERROR_CAMPO_INEXISTENTE || $error === Errores::ERROR_CAMPO_VACIO ) {
                    if( $campo->obligatorio() === false ) {
                        if( $this->sistema->configuracion->activados(Configuracion::LIMPIAR_ERRORES_CAMPOS_OPCIONALES) ) {
                            $campo->error()->limpiar();
                        }
                        return;
                    }
                }

                $todosLosCamposSonValidos = false;
                return;
            }

            foreach( $campo->vextra() as $validacionesExtra ) {
                if( !$validacionesExtra->validar() ) {
                    $todosLosCamposSonValidos = false;
                    break;
                }
            }
        });

        if( $todosLosCamposSonValidos === false ) {
            $this->sistema->actualizarCache = true;
        }

        return $todosLosCamposSonValidos;
    }

    /**
     * Limpia la lista de campos
     *
     * Vacía el array de campos del sistema.
     */
    public function limpiar()
    {
        $this->sistema->campos = [];
        $this->sistema->actualizarCache = true;
    }

    /**
     * Obtiene la lista de campos
     *
     * @return array<string, Campo> Devuelve un array con todos los campos creados.
     */
    public function lista(): array
    {
        return $this->sistema->campos;
    }

}
