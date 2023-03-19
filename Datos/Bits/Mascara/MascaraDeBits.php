<?php

namespace Gof\Datos\Bits\Mascara;

use Gof\Interfaz\Bits\Mascara;

/**
 * Tipo de datos para máscaras de bits
 *
 * Proporciona unas funciones básicas para abstraer el manejo de operaciones a nivel de bits.
 *
 * @package Gof\Datos\Bits\Mascara
 */
class MascaraDeBits implements Mascara
{
    /**
     * @var int Máscara de bits
     */
    private int $marcas;

    /**
     * Constructor
     *
     * @param int $estadoInicial Valor inicial
     */
    public function __construct(int $estadoInicial = 0)
    {
        $this->marcas = $estadoInicial;
    }

    /**
     * Activa los bits pasados por el argumento
     *
     * @param int $bits Bits a activarse en la máscara de bits interna
     *
     * @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function activar(int $bits, int ...$otros): int
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return $this->marcas |= $bits;
    }

    /**
     * Desactiva los bits indicados
     *
     * @param int $bits     Bits a desactivarse en la máscara de bits interna
     * @param int ...$otros Más bits a considerarse (Opcional)
     *
     * @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function desactivar(int $bits, int ...$otros): int
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return $this->marcas &= ~$bits;
    }

    /**
     * Valida si los bits están activos
     *
     * @param int $bits     Bit o bits a validar si están activos
     * @param int ...$otros Más bits a considerarse (Opcional)
     *
     * @return bool Devuelve **true** en caso de que estén **todos** activos o **false** de lo contrario.
     */
    public function activados(int $bits, int ...$otros): bool
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return ($this->marcas & $bits) === $bits;
    }

    /**
     * Valida si los bits están desactivados
     *
     * @param int $bits     Bit o bits a validar si están desactivados
     * @param int ...$otros Más bits a considerarse (Opcional)
     *
     * @return bool Devuelve **true** si están **todos** desactivados o **false** de lo contrario.
     */
    public function desactivados(int $bits, int ...$otros): bool
    {
        foreach( $otros as $masBits ) {
            $bits |= $masBits;
        }

        return ($this->marcas & $bits) === 0;
    }

    /**
     * Define el valor de la máscara de bits
     *
     * @param int $valor Nuevo valor que tendrá la máscara de bits
     *
     * @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function definir(int $valor): int
    {
        return $this->marcas = $valor;
    }

    /**
     * Obtiene el valor de la máscara de bits
     *
     * @return int Devuelve el estado actual de la máscara de bits interna
     */
    public function obtener(): int
    {
        return $this->marcas;
    }

}
