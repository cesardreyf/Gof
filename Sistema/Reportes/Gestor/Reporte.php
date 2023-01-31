<?php

namespace Gof\Sistema\Reportes\Gestor;

use Gof\Contrato\Registro\Registro;
use Gof\Sistema\Reportes\Interfaz\Plantilla;
use Gof\Sistema\Reportes\Interfaz\Reportero;

class Reporte implements Reportero
{
    /**
     *  @var Plantilla Plantilla encargada de traducir los datos recibidos
     */
    private $plantilla;

    /**
     *  @var Registro Gestor de registros encargado de registrar los mensajes
     */
    private $registro;

    /**
     *  @var bool Indica si imprimir o no los mensajes una vez se guarden
     */
    private $imprimir;

    /**
     *  Crea una instancia del gestor de Reporte
     *
     *  @param Registro  $registro  Gestor de registros para los mensajes
     *  @param Plantilla $plantilla Instancia de una plantilla para traducir los datos
     */
    public function __construct(Registro $registro, Plantilla $plantilla)
    {
        $this->imprimir = false;
        $this->registro = $registro;
        $this->plantilla = $plantilla;
    }

    /**
     *  Reporta los datos recibidos
     *
     *  Traduce los datos a una cadena de caracteres, los registra y lo volca al gestor de guardado.
     *  Si está configurado para imprimir, el mensaje guardado será impreso (echo).
     *
     *  @param mixed $datos Datos que serán traducidos, registrado y guardado
     *
     *  @return bool Devuelve TRUE si el reporte fue exitoso, FALSE de lo contrario
     */
    public function reportar($datos): bool
    {
        if( $this->plantilla()->traducir($datos) === false ) {
            return false;
        }

        $mensaje = $this->plantilla()->mensaje();
        $this->registro->registrar($mensaje);

        if( $this->registro->volcar() === false ) {
            return false;
        }

        if( $this->imprimir() === true ) {
            echo $mensaje;
        }

        return true;
    }

    /**
     *  Plantilla usada actualmente
     *
     *  @return Plantilla Devuelve una instancia de la plantilla empleada actualmente
     */
    public function plantilla(): Plantilla
    {
        return $this->plantilla;
    }

    /**
     *  Gestor de registro usado actualmente
     *
     *  @return Registro Devuelve una instancia del gestor de registros empleado actualmente
     */
    public function registro(): Registro
    {
        return $this->registro;
    }

    /**
     *  Configura la impresión de los mensajes reportados
     *
     *  @param bool|null $imprimir Valor booleano para definir la impresión o NULL para obtener el valor actual
     *
     *  @return bool Devuelve TRUE si está activo la impresión o FALSE de los contrario
     */
    public function imprimir(?bool $imprimir = null): bool
    {
        return $imprimir === null ? $this->imprimir : $this->imprimir = $imprimir;
    }

}
