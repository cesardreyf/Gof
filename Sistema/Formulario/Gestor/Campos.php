<?php

namespace Gof\Sistema\Formulario\Gestor;

use Gof\Interfaz\Bits\Mascara;
use Gof\Sistema\Formulario\Gestor\Asignar\AsignarCampo;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;
use Gof\Interfaz\Lista;

/**
 * Gestor de campos
 *
 * Clase encargada de gestionar la validación de los campos.
 *
 * @package Gof\Sistema\Formulario\Gestor
 */
class Campos
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
    public function crear(string $nombreDelCampo, int $tipoDeDato): Campo
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
     * Valida todos los campos registrados
     *
     * Recorre la lista de campos y los valida.
     *
     * @return bool Devuelve **true** si todos los campos son válidos o **false** de lo contrario.
     */
    public function validar(): bool
    {
        $camposValidos = true;

        array_walk($this->sistema->campos, function(Campo $campo) use (&$camposValidos) {
            if( $campo->validar() === false ) {
                $camposValidos = false;
            }
        });

        if( $camposValidos === false ) {
            $this->sistema->actualizarCache = true;
        }

        return $camposValidos;
    }

}
