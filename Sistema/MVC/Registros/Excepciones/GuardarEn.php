<?php

namespace Gof\Sistema\MVC\Registros\Excepciones;

use Gof\Contrato\Registro\Registro;
use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionGuardable;
use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionTraducible;
use Throwable;

/**
 * Gestor de guardado de excepciones
 *
 * Almacena en un registro de mensaje la excepción y luego lo vuelca.
 *
 * @package Gof\Sistema\MVC\Registros\Excepcioness
 */
class GuardarEn implements ExcepcionGuardable
{
    /**
     * @var Registro Instancia del registro de mensajes
     */
    private Registro $registro;

    /**
     * @var ExcepcionTraducible Traductor
     */
    private ExcepcionTraducible $traductor;

    /**
     * Constructor
     *
     * @param Registro            $registro  Registro de mensajes donde se registrarán los errores
     * @param ExcepcionTraducible $traductor Traductor de excepciones a mensaje (string)
     */
    public function __construct(Registro $registro, ExcepcionTraducible $traductor)
    {
        $this->registro = $registro;
        $this->traductor = $traductor;
    }

    /**
     * Guarda el error en el registro
     *
     * Convierte el error en un mensaje, lo registra y luego lo vuelca.
     *
     * @return bool Devuelve el estado de volcado del registro.
     */
    public function guardar(Throwable $excepcion): bool
    {
        $mensaje = $this->traductor->traducir($excepcion);
        $this->registro->registrar($mensaje);
        return $this->registro->volcar();
    }

}
