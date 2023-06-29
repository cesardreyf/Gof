<?php

namespace Gof\Sistema\MVC\Registros\Errores;

use Gof\Contrato\Registro\Registro;
use Gof\Sistema\MVC\Registros\Interfaz\Error;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorGuardable;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorTraducible;

/**
 * Gestor de guardado de errores
 *
 * Almacena en un registro de mensaje el error y luego lo vuelca.
 *
 * @package Gof\Sistema\MVC\Registros\Errores
 */
class GuardarEn implements ErrorGuardable
{
    /**
     * @var Registro Instancia del registro de mensajes
     */
    private Registro $registro;

    /**
     * @var ErrorTraducible Traductor de errores a string
     */
    private ErrorTraducible $traductor;

    /**
     * Constructor
     *
     * @param Registro        $registro  Registro de mensajes donde se registrarán los errores
     * @param ErrorTraducible $traductor Traductor de errores a mensaje (string)
     */
    public function __construct(Registro $registro, ErrorTraducible $traductor)
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
    public function guardar(Error $error): bool
    {
        $mensaje = $this->traductor->traducir($error);
        $this->registro->registrar($mensaje);
        return $this->registro->volcar();
    }

}
