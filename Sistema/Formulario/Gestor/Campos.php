<?php

namespace Gof\Sistema\Formulario\Gestor;

use Gof\Interfaz\Bits\Mascara;
use Gof\Sistema\Formulario\Gestor\Asignar\AsignarCampo;
use Gof\Sistema\Formulario\Interfaz\Campo;
use Gof\Sistema\Formulario\Interfaz\Configuracion;
use Gof\Sistema\Formulario\Validar\ValidarExistencia;
use Gof\Interfaz\Lista;

class Campos
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
     * @var Errores Instancia del gestor de errors
     */
    private Errores $gErrores;

    /**
     * @var Mascara Configuración del sistema
     */
    private Mascara $configuracion;

    /**
     * Constructor
     *
     * @param array &$campos Referencia a la lista de campos.
     * @param array  $datos  Array con los datos del formulario.
     */
    public function __construct(array &$campos, array $datos, Errores $gErrores, Mascara $configuracion)
    {
        $this->datos = $datos;
        $this->campos =& $campos;
        $this->gErrores = $gErrores;
        $this->configuracion = $configuracion;
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
        if( isset($this->campos[$nombreDelCampo]) ) {
            return $this->campos[$nombreDelCampo];
        }

        $campo     = AsignarCampo::segunTipo($nombreDelCampo, $tipoDeDato);
        $siElCampo = new ValidarExistencia($campo, $this->datos);

        if( $siElCampo->existe() ) {
            $campo->valor = $this->datos[$nombreDelCampo];

            if( $this->configuracion->activados(Configuracion::VALIDAR_AL_CREAR) ) {
                $campo->validar();
            }
        }

        $this->gErrores->actualizarCache();
        return $this->campos[$nombreDelCampo] = $campo;
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

        array_walk($this->campos, function(Campo $campo) use (&$camposValidos) {
            if( $campo->validar() === false ) {
                $camposValidos = false;
            }
        });

        if( $camposValidos === false ) {
            $this->gErrores->actualizarCache();
        }

        return $camposValidos;
    }

}
