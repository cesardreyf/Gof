<?php

namespace Gof\Datos\Errores\Mensajes;

use Gof\Interfaz\Errores\Mensajes\ErrorAsociativo as IErrorAsociativo;

/**
 * Almacena errores asociativos múltiples
 *
 * Dato que puede almacenar un conjunto de errores compuestos de códigos y
 * mensajes. A cada error se le puede asignar claves anidadas.
 *
 * La clase no gestiona posibles errores de colisión de claves.
 *
 * @package Gof\Datos\Errores\Mensajes
 */
class ErrorAsociativoMultiple implements IErrorAsociativo
{
    /**
     * @var int Indice para los códigos de errores
     */
    public const CODIGO = 0;

    /**
     * @var int Indice para los mensajes de errores
     */
    public const MENSAJE = 1;

    /**
     * @var array<string, array> Conjunto de errores.
     */
    private array $errores;

    /**
     * @var array Apunta al elemento donde se leerá o escribirá el error.
     */
    private array $puntero;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->limpiar();
    }

    /**
     * Indica si existen errores almacenados
     *
     * Devuelve **true** si hay elementos en la lista de errores.
     *
     * @return bool Devuelve **true** si hay errores o **false** de lo contrario.
     */
    public function hay(): bool
    {
        return !empty($this->errores);
    }

    /**
     * Obtiene el último código de error agregado
     *
     * Si se pasa **null** por parámetro y no existe ningún error registrado
     * con la clave especificada devolverá 0 (cero).
     *
     * @return int Devuelve el último código de error registrado.
     *
     * @see ErrorAsociativo::clave()
     */
    public function codigo(?int $codigo = null): int
    {
        if( $codigo !== null ) {
            $this->puntero[self::CODIGO] = $codigo;
        }

        return $this->puntero[self::CODIGO] ?? 0;
    }

    /**
     * Alias de codigo()
     *
     * @return int
     *
     * @see Error::codigo()
     */
    public function error(): int
    {
        return $this->codigo();
    }

    /**
     * Obtiene el último código de mensaje agregado
     *
     * Si se pasa **null** por parámetro y no existe ningún mensaje de error
     * registrado con la clave determinada devolverá un string vacío.
     *
     * @return string Devuelve el último código de mensaje registrado.
     *
     * @see ErrorAsociativo::clave()
     */
    public function mensaje(?string $mensaje = null): string
    {
        if( $mensaje !== null ) {
            $this->puntero[self::MENSAJE] = $mensaje;
        }

        return $this->puntero[self::MENSAJE] ?? '';
    }

    /**
     * Establece la clave donde se registrarán los errores
     *
     * Especifica las claves asociadas donde se leerán y/o escribirán los
     * errores.
     *
     * @param string $clave     Clave principal (obligatoria).
     * @param string ...$claves Claves anidadas (opcional).
     *
     * @return bool Devuelve **true** si ya existen errores con esta clave.
     */
    public function clave(string $clave, string ...$claves): bool
    {
        array_unshift($claves, $clave);
        $this->puntero =& $this->errores;

        foreach( $claves as $clave ) {
            if( !isset($this->puntero[$clave]) ) {
                $this->puntero[$clave] = [];
            }

            $this->puntero =& $this->puntero[$clave];
        }

        return !empty($elemento);
    }

    /**
     * Limpia los errores almacenados
     *
     * Limpia la pila de errores y establece el código de error a 0.
     */
    public function limpiar()
    {
        $this->errores = [];
        $this->puntero =& $this->errores;
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
