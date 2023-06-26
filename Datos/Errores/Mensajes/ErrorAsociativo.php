<?php

namespace Gof\Datos\Errores\Mensajes;

use Gof\Interfaz\Errores\Mensajes\ErrorAsociativo as IErrorAsociativo;

/**
 * Almacena errores asociativos
 *
 * Dato que puede almacenar un conjunto de errores compuestos de códigos y
 * mensajes. Cada error se le puede asociar una clave.
 *
 * @package Gof\Datos\Errores\Mensajes
 */
class ErrorAsociativo implements IErrorAsociativo
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
     * @var string Almacena la clave donde se escribirá o leera el error.
     */
    private string $clave;

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
        if( $codigo === null ) {
            return ($this->errores[$this->clave][self::CODIGO]) ?? 0;
        }

        return $this->errores[$this->clave][self::CODIGO] = $codigo;
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
        if( $mensaje === null ) {
            return $this->errores[$this->clave][self::MENSAJE] ?? '';
        }

        return $this->errores[$this->clave][self::MENSAJE] = $mensaje;
    }

    /**
     * Establece la clave donde se registrarán los errores
     *
     * Especifica la clave asociada donde se leerán los errores o donde se
     * escribirán.
     *
     * @return bool Devuelve **true** si ya existen errores con esta clave.
     */
    public function clave(string $clave): bool
    {
        $this->clave = $clave;
        return isset($this->errores[$clave]);
    }

    /**
     * Limpia los errores almacenados
     *
     * Limpia la pila de errores y establece el código de error a 0.
     */
    public function limpiar()
    {
        $this->clave = '0';
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
