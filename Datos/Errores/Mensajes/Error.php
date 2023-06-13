<?php

namespace Gof\Datos\Errores\Mensajes;

use Gof\Interfaz\Errores\Mensajes\Error as IError;

/**
 * Dato de tipo error que almacena mensajes y un código
 *
 * Clase de tipo error que puede almacenar una lista de mensajes y un código de error.
 *
 * @package Gof\Datos\Errores\Mensajes
 */
class Error implements IError
{
    /**
     * @var int Código de error.
     */
    private int $codigo;

    /**
     * @var array Lista de mensajes de errores.
     */
    private array $errores;

    /**
     * Constructor
     *
     * @param int   $codigoDeError  Código de error (Opcional).
     * @param array $erroresPrevios Array con mensajes de errores previos (Opcional).
     */
    public function __construct(int $codigoDeError = 0, array $erroresPrevios = [])
    {
        $this->codigo  = $codigoDeError;
        $this->errores = $erroresPrevios;
    }

    /**
     * Indica si existen errores
     *
     * Devuelve **true** si hay mensjes de errores en la pila o si el código de
     * error es diferente de cero.
     *
     * @return bool Devuelve **true** si hay errores o **false** de lo contrario.
     */
    public function hay(): bool
    {
        return !empty($this->errores) || $this->codigo !== 0;
    }

    /**
     * Obtiene el código de error
     *
     * @param ?int $codigo Nuevo código de error o **null** para obtener el actual.
     *
     * @return int Devuelve el código de error actual.
     */
    public function codigo(?int $codigo = null): int
    {
        return $codigo === null ? $this->codigo : $this->codigo = $codigo;
    }

    /**
     * Obtiene último mensaje o define uno nuevo
     *
     * Si se pasa un mensaje como argumento se agregará un nuevo mensaje a la
     * lista de errores. Si no se pasa nada por parámetro se obtendrá el último
     * mensaje agregado.
     *
     * Si no hay mensajes almacenados en la pila de errores se devolverá un string vacío.
     *
     * @param ?string $mensaje Nuevo mensaje de error o **null** para obtener el último.
     *
     * @return string Devuelve el último mensaje de la pila de errores.
     */
    public function mensaje(?string $mensaje = null): string
    {
        if( $mensaje === null ) {
            $tope = count($this->errores) - 1;
            return $tope < 0 ? '' : $this->errores[$tope];
        }

        return $this->errores[] = $mensaje;
    }

    /**
     * Quita el último mensaje de la pila de errores
     *
     * Obtiene el mensaje del tope de la pila de errores y luego lo remueve.
     *
     * **NOTA**: Esto no afecta al código de errores.
     *
     * @return string Devuelve un mensaje de error
     */
    public function quitar(): string
    {
        return array_pop($this->errores) ?? '';
    }

    /**
     * Limpia los errores almacenados
     *
     * Limpia la pila de errores y establece el código de error a 0.
     */
    public function limpiar()
    {
        $this->codigo = 0;
        $this->errores = [];
    }

    /**
     * Lista de errores
     *
     * @return array Devuelve la pila de errores.
     */
    public function lista(): array
    {
        return $this->errores;
    }

}
