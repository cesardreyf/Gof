<?php

namespace Gof\Sistema\MVC\Registros\Excepciones;

use Gof\Gestor\Mensajes\Guardar\GuardarEnArchivo;
use Gof\Gestor\Registro\Simple\RegistroSimple;
use Gof\Interfaz\Archivos\Archivo;
use Gof\Interfaz\Lista\Datos;

/**
 * Gestor de excepciones simple
 *
 * Simplifica la operación de guardado e impresión de excepciones.
 *
 * @package Gof\Sistema\MVC\Registros\Excepciones
 */
class Simple
{
    /**
     * @var ?Archivo Almacena la instancia del archivo donde se guardarán los errores
     */
    private ?Archivo $archivo = null;

    /**
     * @var Datos
     */
    private Datos $guardado;

    /**
     * @var Datos
     */
    private Datos $impresion;

    /**
     * Constructor
     *
     * @param Datos $gestoresDeGuardado  Instancia del gestor de guardado
     * @param Datos $gestoresDeImpresion Instancia del gestor de impresión
     */
    public function __construct(Datos $gestoresDeGuardado, Datos $gestoresDeImpresion)
    {
        $this->guardado = $gestoresDeGuardado;
        $this->impresion = $gestoresDeImpresion;
    }

    public function guardarEn(Archivo $archivo)
    {
        if( is_null($this->archivo) ) {
            $this->archivo = $archivo;
            $traductor = new Traductor();
            $registro = new RegistroSimple(new GuardarEnArchivo($this->archivo));
            $this->guardado->agregar(new GuardarEn($registro, $traductor));
            $this->impresion->agregar(new Impresor($traductor));
        }
    }

}
